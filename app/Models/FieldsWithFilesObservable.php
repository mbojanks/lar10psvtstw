<?php
namespace App\Models;

use App\Observers\FieldsWithFilesObserver;

trait FieldsWithFilesObservable {
    /*
    protected $dispatchesEvents = [
        'creating' => FieldsWithFilesObserver::class,
        'updating' => FieldsWithFilesObserver::class,
        'deleted' => FieldsWithFilesObserver::class,
    ]; NOT NEEDED? */
    //model should have fieldsWithFile
    public static function bootFieldsWithFilesObservable() {
        self::observe(FieldsWithFilesObserver::class);
    }
}
