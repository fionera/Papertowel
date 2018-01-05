<?php

namespace App\Components\Theme;


interface ThemeInterface
{
    public function getName(): string;

    public function getAuthor(): ?string;

    public function getDependencies(): array;

    public function getCss(): array;

    public function getJavascript(): array;
}