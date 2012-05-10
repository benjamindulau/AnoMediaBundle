<?php

namespace Ano\Bundle\MediaBundle;

final class MediaEvents
{
    const BEFORE_PREPARE = 'ano_media.before_prepare';
    const AFTER_PREPARE = 'ano_media.after_prepare';

    const BEFORE_SAVE = 'ano_media.before_save';
    const AFTER_SAVE = 'ano_media.after_save';

    const BEFORE_UPDATE = 'ano_media.before_update';
    const AFTER_UPDATE = 'ano_media.after_update';

    const BEFORE_REMOVE = 'ano_media.before_remove';
    const AFTER_REMOVE = 'ano_media.after_remove';
}