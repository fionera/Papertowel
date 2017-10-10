<?php

namespace Papertowel\View;

use Papertowel\Models\TemplateBlock;
use Smarty_Resource_Custom;

class TemplateBlockResource extends Smarty_Resource_Custom
{
    /**
     * Fetch a template and its modification time from database
     *
     * @param string $name template name
     * @param string|null $source template source
     * @param integer|null $mtime template modification timestamp (epoch)
     * @return void
     */
    protected function fetch($name, &$source, &$mtime)
    {
        /** @var TemplateBlock $templateBlock */
        $templateBlock = Papertowel()->Database()->getRepository(TemplateBlock::class)->findOneBy(['templateBlockName' => $name]);

        if ($templateBlock !== null) {
            $source = $templateBlock->getContent();
            $mtime = time();
        } else {
            $source = null;
            $mtime = null;
        }
    }
}