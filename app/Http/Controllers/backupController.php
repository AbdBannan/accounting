<?php
//
namespace App\Http\Controllers;
//
//use Illuminate\Http\Request;
//
//class backupController extends Controller
//{
//    //
//}


//namespace Backpack\BackupManager\app\Http\Controllers;

use App\functions\globalFunctions;
use Illuminate\Support\Facades\Artisan;
use Exception;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Log;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Storage;
use phpDocumentor\Reflection\Types\True_;

class backupController extends Controller
{
    public function index()
    {
//        if (!count(config('backup.backup.destination.disks'))) {
//            dd(trans('backpack::backup.no_disks_configured'));
//        }

        $this->data['backups'] = [];

        foreach (config('backup.backup.destination.disks') as $disk_name) {
            $disk = Storage::disk($disk_name);
            $can_download = config("filesystems.disks.$disk_name.driver") == 'local';
            $files = $disk->allFiles();

            // make an array of backup files, with their filesize and creation date
            foreach ($files as $k => $f) {
                // only take the zip files into account
                if (substr($f, -4) == '.zip' && $disk->exists($f)) {
                    $this->data['backups'][] = [
                        'file_path' => $f,
                        'file_name' => str_replace('backups/', '', $f),
                        'file_size' => $disk->size($f),
                        'last_modified' => $disk->lastModified($f),
                        'disk' => $disk_name,
                        'download' => $can_download ? true : false,
                    ];
                }
            }
        }

        // reverse the backups, so the newest one would be on top
        $this->data['backups'] = array_reverse($this->data['backups']);
        $this->data['title'] = 'Backups';
        globalFunctions::registerUserActivityLog("seen_all","backups",null);
        return view('admin.backups.backups', $this->data);
    }

    public function create()
    {

        $message = 'success';

        try {
            ini_set('max_execution_time', 600);

            Log::info('Backpack\BackupManager -- Called backup:run from admin interface');

            Artisan::call('backup:run');

            $output = Artisan::output();
            if (strpos($output, 'Backup failed because')) {
                preg_match('/Backup failed because(.*?)$/ms', $output, $match);
                $message = "Backpack\BackupManager -- backup process failed because ";
                $message .= isset($match[1]) ? $match[1] : '';
                Log::error($message . PHP_EOL . $output);
                globalFunctions::flashMessage("create",false,"backup");
            } else {
                Log::info("Backpack\BackupManager -- backup process has started");
                globalFunctions::flashMessage("create",true,"backup");
            }
        } catch (Exception $e) {
            Log::error($e);

            return Response::make($e->getMessage(), 500);
        }
        globalFunctions::registerUserActivityLog("create","backup",null);

        return back();
    }

    /**
     * Downloads a backup zip file.
     */
    public function download()
    {
        $disk_name = $_GET["disk"];
//        $disk_name = Request::input('disk');
        $disk = Storage::disk($disk_name);
        $file_name = $_GET["file_name"];
//        $file_name = Request::input('file_name');
        $can_download = config("filesystems.disks.$disk_name.driver") == 'local';
        if ($can_download) {
            if ($disk->exists($file_name)) {
                globalFunctions::registerUserActivityLog("downloaded","backup",null);
                if (method_exists($disk->getAdapter(), 'getPathPrefix')) {
                    $storage_path = $disk->getAdapter()->getPathPrefix();
                    return response()->download($storage_path . $file_name);
                } else {
                    return $disk->download($file_name);
                }
            } else {
                abort(404, trans('backpack::backup.backup_doesnt_exist'));
            }
        } else {
            abort(404, trans('backpack::backup.only_local_downloads_supported'));
        }
    }

    /**
     * Deletes a backup file.
     */
    public function delete(Request $request,$file_name)
    {
        $diskName = $_GET["disk"];
//        $diskName = Request::input('disk');

        if (!in_array($diskName, config('backup.backup.destination.disks'))) {
            abort(500, trans('backpack::backup.unknown_disk'));
        }

        $disk = Storage::disk($diskName);
        $result = null;
        if ($file_name!=-1){
            if ($disk->exists($file_name)) {
                $disk->delete($file_name);
                globalFunctions::registerUserActivityLog("deleted","backup",null);
            } else {
                $result = false;
            }
        } else if (isset($request["multi_files_name"])) {
            $files_name = $request["multi_files_name"];
            foreach ($files_name as $file_name) {
                if ($disk->exists($file_name)) {
                    $disk->delete($file_name);
                    globalFunctions::registerUserActivityLog("deleted", "backup", null);
                } else{
                    $result = false;
                }
            }
        }
        if (!$result){
            globalFunctions::flashMessage("delete",true,"backup");
        } else {
            globalFunctions::flashMessage("delete",false,"backup");
        }
        return back();
    }
}
