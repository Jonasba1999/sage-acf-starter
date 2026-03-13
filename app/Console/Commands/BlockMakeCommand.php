<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Str;

class BlockMakeCommand extends Command {
    protected $signature = 'make:block {name}';
    protected $description = 'Generate a full ACF block with Blade view, Composer, ACF JSON, preview image';

    public function handle() {
        $input = $this->argument('name');

        $pascal = Str::studly($input);
        $kebab = Str::kebab(strtolower($input));
        $snake = str_replace('-', '_', $kebab);

        // For a readable title, convert to Title Case
        $title = Str::title(str_replace(['-', '_'], ' ', $input));

        $themePath = get_template_directory();
        $blockDir = "$themePath/app/Blocks/{$pascal}";
        $viewPath = "$themePath/resources/views/blocks/{$kebab}.blade.php";
        $acfPath = "$themePath/acf-json/group_block_{$snake}.json";
        $composerPath = "$themePath/app/View/Composers/Block{$pascal}.php";
        $previewPath = "$blockDir/preview.jpg";

        if (!is_dir($blockDir)) mkdir($blockDir, 0777, true);
        if (!is_dir(dirname($viewPath))) mkdir(dirname($viewPath), 0777, true);
        if (!is_dir(dirname($composerPath))) mkdir(dirname($composerPath), 0777, true);
        if (!is_dir("$themePath/acf-json")) mkdir("$themePath/acf-json", 0777, true);

        // block.json
        file_put_contents("{$blockDir}/block.json", json_encode([
            'name' => "acf/{$kebab}",
            'title' => $title,
            'category' => 'brand-category',
            'icon' => 'layout',
            'description' => '',
            'acf' => [
                'mode' => 'preview',
                'renderCallback' => 'App\\renderAcfBlock',
                'postTypes' => ['page'],
            ],
            'textdomain' => wp_get_theme()->get('TextDomain'),
            'keywords' => [],
            'supports' => [
                'align' => false,
                'mode' => false,
                'anchor' => true
            ],
            'example' => [
                'attributes' => [
                    'mode' => 'preview',
                    'data' => ['is_preview' => 1]
                ]
            ],
        ], JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES));

        // ACF field group (only in acf-json folder)
        $acfFields = [
            'key' => "group_block_{$snake}",
            'title' => '[BLOCK] ' . $title,
            'fields' => [
                [
                    'key' => "field_block_{$snake}_content",
                    'name' => "{$snake}_group",
                    'type' => 'group',
                    'layout' => 'block',
                    'sub_fields' => []
                ]
            ],
            'location' => [
                [
                    [
                        'param' => 'block',
                        'operator' => '==',
                        'value' => "acf/{$kebab}"
                    ]
                ]
            ],
            'active' => true,
            'modified' => time(),
            '_local' => 'json'
        ];

        file_put_contents($acfPath, json_encode($acfFields, JSON_PRETTY_PRINT));

        // Blade template
        file_put_contents($viewPath, <<<BLADE
<section @if (!empty(\$block['anchor'])) id="{{ \$block['anchor'] }}" @endif class="block block-{$kebab}">
    <div class="container"></div>
</section>
BLADE);

        // Composer class
        file_put_contents(
            $composerPath,
            <<<PHP
<?php

namespace App\View\Composers;

use Roots\Acorn\View\Composer;
use Illuminate\Support\Arr;

class Block{$pascal} extends Composer
{
    protected static \$views = [
        'blocks.{$kebab}'
    ];

    public function with()
    {
        \$block = ACF::post('{$snake}_group');

        return [
           // 'field' => Arr::get(\$block, 'field'),
        ];
    }
}
PHP
        );

        // Empty preview image
        file_put_contents($previewPath, '');

        $this->info("✅ Block '{$pascal}' created successfully!");
        $this->info("📝 Field group created in acf-json/ — edit fields in ACF admin.");
    }
}
