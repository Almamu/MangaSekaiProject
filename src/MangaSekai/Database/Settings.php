<?php

namespace MangaSekai\Database;

use MangaSekai\Database\Base\Settings as BaseSettings;

/**
 * Skeleton subclass for representing a row from the 'settings' table.
 *
 *
 *
 * You should add additional methods to this class to meet the
 * application requirements.  This class will only be generated as
 * long as it does not already exist in the output directory.
 *
 */
class Settings extends BaseSettings
{
    public function getValue ()
    {
        return json_decode (parent::getValue ());
    }
    
    public function setValue($value)
    {
        return parent::setValue (json_encode ($value, JSON_BIGINT_AS_STRING | JSON_THROW_ON_ERROR | JSON_INVALID_UTF8_SUBSTITUTE));
    }
}
