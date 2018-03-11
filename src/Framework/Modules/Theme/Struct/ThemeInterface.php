<?php

namespace Papertowel\Framework\Modules\Theme\Struct;

interface ThemeInterface
{
    public function getName(): string;

    public function getAuthor(): ?string;

    public function getDependency(): string;

    public function getCss(): array;

    public function getJavascript(): array;
}
