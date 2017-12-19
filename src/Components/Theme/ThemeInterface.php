<?php

namespace Components\Theme;


interface ThemeInterface
{
    public function getName(): string;

    public function getAuthor(): ?string;

    public function getExtend(): ?string;

    public function getCss(): array;

    public function getJavascript(): array;
}