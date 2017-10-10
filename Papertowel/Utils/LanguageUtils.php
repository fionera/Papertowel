<?php

namespace Papertowel\Utils;

use Papertowel\Exceptions\LangNotFoundException;
use Papertowel\Models\Language;
use Papertowel\Papertowel;

class LanguageUtils
{

    /**
     * @param string $languageString
     * @param Language[] $languages
     * @return bool
     */
    public static function isValidLanguageString(string $languageString, $languages = null): bool
    {
        if ($languages === null) {
            /** @var Language[] $languages */
            $languages = Papertowel()->Database()->getRepository(Language::class)->findAll();
        }

        foreach ($languages as $language) {
            if ($languageString === $language->getLanguageString()) {
                return true;
            }
        }

        return false;
    }

    public static function getDefaultLanguageObject()
    {
        $defaultLanguageObject = self::LanguageObjectByStringFromDb(Papertowel()->Config()->getDefaultLanguage());

        if ($defaultLanguageObject === null) {
            throw new LangNotFoundException(Papertowel()->Config()->getDefaultLanguage());
        }

        return $defaultLanguageObject;
    }

    public static function LanguageObjectByStringFromDb(string $languageString)
    {
        return Papertowel()->Database()->getRepository(Language::class)
            ->findOneBy(['languageString' => $languageString]);
    }
}