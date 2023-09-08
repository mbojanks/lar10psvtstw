<?php

namespace App\Observers;

use Illuminate\Support\Str;
use Illuminate\Http\UploadedFile;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class FieldsWithFilesObserver
{
    protected $origValue;
    protected $curValue;
    protected $fileOptions;
    protected $fromDelete;

    protected function uploadFiles(Model &$model) {
        if (isset($model->fieldsWithFile)) {
            $fileoptions = $model->fieldsWithFile;
            foreach($fileoptions as $fF => $fileopts) {
                if (isset($model->{$fF})) { // property_exists($model, $fF) not working correctly - false although if actually exists!!!
                    $file = $model->{$fF};
                    if ($file instanceof UploadedFile) {
                        $ext = $file->extension();
                        if (isset($fileopts['filePath'])) {
                            $path = $fileopts['filePath'];
                        } else {
                            $path = '';
                        }
                        if (isset($fileopts['fileDriver'])) {
                            $disk = $fileopts['fileDriver'];
                        }
                        $public = false;
                        if (isset($fileopts['public'])) {
                            $public = $fileopts['public'];
                        } else if (isset($disk)) {
                            $public = (config("filesystems.disks.$disk.visibility") == 'public');
                        }
                        if ($file->isValid()) { // there were no problems uploading the file - it should be stored then
                            $name = Str::random(10);
                            if (!isset($disk)) { // filePath is the absolute path in this case!?
                                $url = Storage::putFileAs($path, $file, "$name.$ext");
                            } else {
                                if ($public) { // use disk driver - and filePath is then relative to driver folder
                                    $url = config("filesystems.disks.$disk.url") . '/';
                                } else { // use root from disk driver
                                    $url = config("filesystems.disks.$disk.root") . '/'; // $url = '' ?
                                }
                                $url .= $file->storeAs($path, "$name.$ext", $disk); // it could be also with Storage
                            }
                            $model->{$fF} =  $url;
                            continue; // skip return false
                        } else {
                            $model->{$fF} = 'File is not valid!';
                        }
                    } else {
                        //NOTHING! $model->{$fF} = 'Not a file!';
                    }
                } else {
                    $model->{$fF} = "No value for the field $fF";
                }
                if ($fileopts['fileRequired'])
                    return false;
            }
            return true; // default already?
        } // else do nothing - no guessing!
    }
    //
    public function creating(Model $model) {
        return $this->uploadFiles($model);
    }

    public function updating(Model $model) {
        $result = $this->uploadFiles($model);
        return $result;
    }

    public function updated(Model $model) {
        // remove the unused file
        $this->fileOptions = $model->fieldsWithFile;
        $this->origValue = $model->getOriginal();
        $this->curValue = $model->getAttributes();
        $this->fromDelete = false;
        $this->removeFiles();
    }

    public function deleted(Model $model) {
        // remove the unused file
        $this->fileOptions = $model->fieldsWithFile;
        $this->origValue = $model->getOriginal();
        $this->curValue = $model->getAttributes();
        $this->fromDelete = true;
        $this->removeFiles();
    }

    protected function removeFiles() {
        if (isset($this->fileOptions)) {
            $fileoptions = $this->fileOptions;
            $existing = $this->origValue;
            $cur = $this->curValue;
            foreach($fileoptions as $fF => $fileopts) {
                if (($existing[$fF] != $cur[$fF]) || ($this->fromDelete)) {
                    if (isset($fileopts['fileDriver'])) {
                        $disk = $fileopts['fileDriver'];
                    }
                    $public = false;
                    if (isset($fileopts['public'])) {
                        $public = $fileopts['public'];
                    } else if (isset($disk)) {
                        $public = (config("filesystems.disks.$disk.visibility") == 'public');
                    }
                    if (isset($disk) && $public) {
                        $img = \str_replace(config("filesystems.disks.$disk.url") . '/', '', $existing[$fF]);
                        if (Storage::disk($disk)->exists($img)) { // remove the old one
                            Storage::disk($disk)->delete($img);
                        }
                    } else {
                        $img = $existing[$fF];
                        if (Storage::exists($img)) {
                            Storage::delete($img);
                        }
                    }
                }
            }
        }
    }
}
