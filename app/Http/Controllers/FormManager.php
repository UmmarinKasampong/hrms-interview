<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use App\Models\reDocuments;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;
// use File;
class FormManager extends Controller
{


    function CountTableStatus($tname)
    {
        $user_id = auth()->user()->id;

        if (auth()->user()->department !== 'Manager') {
            $quary = "SELECT 
            (SELECT COUNT(*) FROM $tname where create_by = $user_id) AS ALL_Data,
             (SELECT COUNT(*) FROM $tname where status_form = 'Wait Progress'  and create_by = $user_id  ) AS Wait_Progress,
             (SELECT COUNT(*) FROM $tname where status_form = 'Reject' and create_by = $user_id) AS Reject,
             (SELECT COUNT(*) FROM $tname where status_form = 'Approve' and create_by = $user_id) AS Approve,
             ($user_id)as EmployeeId";
        } else {
            $quary = "SELECT 
            (SELECT COUNT(*) FROM $tname where to_manager = $user_id) AS ALL_Data,
             (SELECT COUNT(*) FROM $tname where status_form = 'Wait Progress'  and to_manager = $user_id  ) AS Wait_Progress,
             (SELECT COUNT(*) FROM $tname where status_form = 'Reject' and to_manager = $user_id) AS Reject,
             (SELECT COUNT(*) FROM $tname where status_form = 'Approve' and to_manager = $user_id) AS Approve,
             ($user_id)as ManagerId";
        }

        return  $quary;
    }

    function LoadMenu(Request $req)
    {
        $title = $req->title;



        if ($title === "ขอเอกสารสำคัญ") {
            $q = $this->CountTableStatus('re_documents');
            $data = DB::select($q);
        } else if ($title === "ขอลา") {
            $q = $this->CountTableStatus('absences');
            $data = DB::select($q);
        } else {
            $q = $this->CountTableStatus('offsite_works');
            $data = DB::select($q);
        }

        // dd($data);

        // return response()->json(['data' => $data]);
        $viewContent = View::make('components.fretrue.Fmain', compact('title', 'data'))->render();
        return response()->json(['view' => $viewContent]);
    }



    function Overview()
    {

        // Choose Table and count table

        $user_id = auth()->user()->id;

        if (auth()->user()->department !== 'Manager') {
            $quary = "SELECT 
           (SELECT COUNT(*) FROM absences WHERE create_by = $user_id) AS absences,
           (SELECT COUNT(*) FROM offsite_works WHERE create_by = $user_id) AS offsite_works,
           (SELECT COUNT(*) FROM re_documents WHERE create_by = $user_id) AS re_documents,
            ($user_id)as EmployeeId";
        } else {
            $quary = "SELECT 
            (SELECT COUNT(*) FROM absences WHERE to_manager = $user_id) AS absences,
            (SELECT COUNT(*) FROM offsite_works WHERE to_manager = $user_id) AS offsite_works,
            (SELECT COUNT(*) FROM re_documents WHERE to_manager = $user_id) AS re_documents,
            ($user_id)as ManagerId";
        }
        $data = DB::select($quary);

        $viewContent = View::make('components.overview', compact('data'))->render();
        return response()->json(['view' => $viewContent]);
    }





    // Table


    function FillterTable(Request $req)
    {

        $table = $req->table;
        $quary = $req->quary;
        $user_id = auth()->user()->id;
        $username = auth()->user()->name;

        $user_filter =  (auth()->user()->department !== 'Manager') ? "t1.create_by = $user_id" : "t1.to_manager = $user_id";

        // quary fillter
        if ($quary === "All") {
            $fillter = '';
        } else if ($quary === "Wait") {
            $fillter = "and t1.status_form = 'Wait Progress'";
        } else if ($quary === "Reject") {
            $fillter = "and t1.status_form = 'Reject'";
        } else if ($quary === "Approve") {
            $fillter = "and t1.status_form = 'Approve'";
        }


        if ($table === 'ขอเอกสารสำคัญ') {
            $quary = "SELECT t1.*, t2.name, '$username' AS NameUser 
             FROM re_documents t1
             LEFT JOIN users t2 ON t1.create_by = t2.id 
             WHERE $user_filter $fillter";

            $data =  DB::select($quary);
            $viewContent = View::make('components.fretrue.table.docT', compact('data', 'quary'))->render();
        } else if ($table === 'ขอลา') {
            $quary = "SELECT t1.*, t2.name, '$username' AS NameUser 
            FROM absences t1
            LEFT JOIN users t2 ON t1.create_by = t2.id 
            WHERE $user_filter $fillter";

            $data =  DB::select($quary);
            $viewContent = View::make('components.fretrue.table.leaveT', compact('data'))->render();
        } else {
            $quary = "SELECT t1.*, t2.name, '$username' AS NameUser 
             FROM offsite_works t1 
             LEFT JOIN users t2 ON t1.create_by = t2.id 
             WHERE $user_filter $fillter";

            $data = DB::select($quary);
            $viewContent = View::make('components.fretrue.table.outoffT', compact('data'))->render();
        }


        return response()->json(['view' => $viewContent]);
        // return response()->json(['table' => $table , 'quary' => $quary ]);
    }




    // Form


    // View
    function ViewdocForm($id)
    {
        $quary = "SELECT t1.* , t2.name as req_name FROM re_documents t1 LEFT JOIN users t2 ON t1.create_by = t2.id  where doc_id = $id";
        $mangerInfo = DB::table('users')->where('department', 'Manager')->get();
        $data =  DB::select($quary);
        return view('components.fretrue.Form.viewForm.docForm', compact('data', 'mangerInfo'));
    }


    function ViewleaveForm($id)
    {
        $quary = "SELECT t1.* , t2.name as req_name FROM absences t1 LEFT JOIN users t2 ON t1.create_by = t2.id where absence_id = $id";
        $mangerInfo = DB::table('users')->where('department', 'Manager')->get();
        $data =  DB::select($quary);

        return view('components.fretrue.Form.viewForm.leaveForm', compact('data', 'mangerInfo'));
    }

    function ViewoutForm($id)
    {
        $quary = "SELECT t1.* , t2.name as req_name FROM offsite_works t1 LEFT JOIN users t2 ON t1.create_by = t2.id where offsite_id  = $id";
        $mangerInfo = DB::table('users')->where('department', 'Manager')->get();
        $data =  DB::select($quary);
        return view('components.fretrue.Form.viewForm.outForm', compact('data', 'mangerInfo'));
    }

    function ManagerAction(Request $req)
    {
        $form_id = $req->form_id;
        $form_rejectDecs = $req->reject_desc;
        $form_new_status = $req->Maction;
        $table = $req->form_title;
        $table_id = $req->table_id;
        // if($form_title === 'Doc'){
        //     $title = 're_documents';
        // }else if($form_title === 'Leave'){
        //     $title = 'absences';
        // }else {
        //     $title = 'offsite_works';
        // }

        // Build the update query
        $query = "UPDATE $table
        SET reject_desc = ?, 
            status_form = ?
        WHERE $table_id = ?";

        // Execute the update query
        $rowsAffected = DB::update($query, [$form_rejectDecs, $form_new_status, $form_id]);

        if ($rowsAffected === 0) {
            return response()->json(['error' => "Update Manager Action Fail"]);
        }
        return response()->json(['success' => "Update Manager Success"]);
    }


    // Update

    function UpdatedocForm($id)
    {
        $quary = "SELECT t1.* , t2.name as req_name FROM re_documents t1 LEFT JOIN users t2 ON t1.create_by = t2.id  where doc_id = $id";
        $mangerInfo = DB::table('users')->where('department', 'Manager')->get();
        $data =  DB::select($quary);

        return view('components.fretrue.Form.editForm.docEdit', compact('data', 'mangerInfo'));
    }


    function UpdatedocPost(Request $req)
    {
        $customMessages = [
            'type_doc.required' => 'กรุณาใส่ประเภทเอกสาร',
            'doc_lang.required' => 'กรุณาเลือกภาษา',

            'rec_form.required' => 'กรุณาเลือกรูปแบบการรับเอกสาร',
            'doc_amount.required' => 'กรุณาเลือกจำนวนเอกสาร',
            'doc_Y.required' => 'กรุณาเลือกปีของเอกสาร',
            'doc_M.required' => 'กรุณาเลือกเดือนของเอกสาร',
            'to_menager.required' => 'กรุณาเลือกหัวหน้าของคุณ'
            // Add more custom messages as needed
        ];

        $validator = Validator::make($req->all(), [
            'type_doc' => 'required',
            'doc_lang' => 'required',
            'rec_form' => 'required',
            'doc_amount' => 'required',
            'doc_Y' => 'required',
            'doc_M' => 'required',
            'to_menager' => 'required'

        ], $customMessages);


        if ($validator->fails()) {
            // Handle validation failure, return error response or redirect back with errors
            return response()->json(['error' => $validator->errors()]);
        }


        $now = Carbon::now();
        $data['doc_type'] = $req->type_doc;
        $data['doc_lang'] = $req->doc_lang;
        $data['doc_pick'] = $req->rec_form;
        $data['doc_amount'] = (int)$req->doc_amount;
        $data['doc_Y'] = (int)$req->doc_Y;
        $data['doc_M'] = $req->doc_M;
        $data['doc_desc'] = $req->doc_desc;
        $data['to_manager'] = (int)$req->to_menager;
        $data['status_form'] = 'Wait Progress';
        $data['updated_at'] = $now;


        // Update data in the database
        $updatedRows = DB::table('re_documents')
            ->where('doc_id', $req->form_id)
            ->update($data);

        if ($updatedRows === 0) {
            return response()->json(['error' => "Update failed"]);
        }
        return response()->json(['success' => "Update Form Success", 'data' => $data]);
    }


    function UpdateleaveForm($id)
    {
        $quary = "SELECT t1.* , t2.name as req_name FROM absences t1 LEFT JOIN users t2 ON t1.create_by = t2.id where absence_id = $id";
        $mangerInfo = DB::table('users')->where('department', 'Manager')->get();
        $data =  DB::select($quary);

        return view('components.fretrue.Form.editForm.leaveEdit', compact('data', 'mangerInfo'));
    }


    function UpdateleavePost(Request $req)
    {
        $uuid = Str::uuid();
        $customMessages = [
            'type_leave.required' => 'กรุณาใส่ประเภทการลา',
            's_date.required' => 'กรุณาเลือกวันเริ่มต้นลา',

            'e_date.required' => 'กรุณาเลือกวันสุดท้ายที่ลา',
            'leave_desc.required' => 'กรุณาใส่เหตุผลการลา',
            'to_menager.required' => 'กรุณาเลือกหัวหน้าของคุณ'

            // Add more custom messages as needed
        ];

        $validator = Validator::make($req->all(), [
            'type_leave' => 'required',
            's_date' => 'required',
            'e_date' => 'required',
            'leave_desc' => 'required',
            'to_menager' => 'required'

        ], $customMessages);


        if ($validator->fails()) {
            // Handle validation failure, return error response or redirect back with errors
            return response()->json(['error' => $validator->errors()]);
        }

        // check del file
        if ($req->del_file) {

            $fileName = $req->old_file_path; // Update with the actual filename
            $path = 'uploads/leaveDoc/' . $fileName;

            // ลบไฟล์เก่า
            if (Storage::disk('public')->exists($path)) {
                // Delete the file
                Storage::disk('public')->delete($path);
                $msg = "File deleted successfully.";
            } else {
                $msg = "File does not exist.";
            }
            // ใส่ไฟล์ใหม่
            if ($req->file('leave_file')) {
                $file = $req->file('leave_file'); // Retrieve the uploaded file from the request
                $filename = $uuid . "." . $file->getClientOriginalExtension(); // Retrieve the original filename

                $destination_path = "public/uploads/leaveDoc/";
                // Storage::disk('local')->put('uploads/userImgs/'.$filename, file_get_contents($file));
                $path = $file->storeAs($destination_path, $filename);
            } else {
                $filename = '';
            }

            $data['absence_path'] = $filename;
        } else {
            $msg = "ไม่มีการลบไฟล์";

            // ถ้ามีการใส่ไฟล์ กรณีไม่มีไฟล์อยู่แล้ว
            if ($req->file('leave_file')) {
                $file = $req->file('leave_file'); // Retrieve the uploaded file from the request
                $filename = $uuid . "." . $file->getClientOriginalExtension(); // Retrieve the original filename

                $destination_path = "public/uploads/leaveDoc/";
                // Storage::disk('local')->put('uploads/userImgs/'.$filename, file_get_contents($file));
                $path = $file->storeAs($destination_path, $filename);
                $data['absence_path'] = $filename;
            }
        }


        $now = Carbon::now();
        $data['absence_type'] = $req->type_leave;
        $data['absence_start'] = $req->s_date;
        $data['absence_end'] = $req->e_date;
        $data['absence_desc'] = $req->leave_desc;

        $data['status_form'] = 'Wait Progress';
        $data['to_manager'] = (int)$req->to_menager;
        $data['updated_at'] = $now;


        // Update data in the database
        $updatedRows = DB::table('absences')
            ->where('absence_id', $req->form_id)
            ->update($data);

        if ($updatedRows === 0) {
            return response()->json(['error' => "Update failed"]);
        }
        return response()->json(['success' => "Update Form Success", 'msg' => $msg, 'all' => $data]);
    }



    function UpdateoutForm($id)
    {
        $quary = "SELECT t1.* , t2.name as req_name FROM offsite_works t1 LEFT JOIN users t2 ON t1.create_by = t2.id where offsite_id  = $id";
        $mangerInfo = DB::table('users')->where('department', 'Manager')->get();
        $data =  DB::select($quary);

        return view('components.fretrue.Form.editForm.outEdit', compact('data', 'mangerInfo'));
    }

    function UpdateoutPost(Request $req)
    {
        $uuid = Str::uuid();
        $customMessages = [

            's_date.required' => 'กรุณาเลือกวันเริ่มต้น',
            'e_date.required' => 'กรุณาเลือกวันสุดท้าย',
            'out_place.required' => 'กรุณาใส่ชื่อสถานที่',
            'out_direc.required' => 'กรุณาใส่ที่อยู่',
            'out_desc.required' => 'กรุณาใส่รายละเอียด',
            'to_menager.required' => 'กรุณาเลือกหัวหน้าของคุณ'
            // Add more custom messages as needed
        ];

        $validator = Validator::make($req->all(), [
            's_date' => 'required',
            'e_date' => 'required',
            'out_place' => 'required',
            'out_direc' => 'required',
            'out_desc' => 'required',
            'to_menager' => 'required'

        ], $customMessages);


        if ($validator->fails()) {
            // Handle validation failure, return error response or redirect back with errors
            return response()->json(['error' => $validator->errors()]);
        }


        // check del file
        if ($req->del_file) {

            $fileName = $req->old_file_path; // Update with the actual filename
            $path = 'uploads/outDoc/' . $fileName;

            // ลบไฟล์เก่า
            if (Storage::disk('public')->exists($path)) {
                // Delete the file
                Storage::disk('public')->delete($path);
                $msg = "File deleted successfully.";
            } else {
                $msg = "File does not exist.";
            }
            // ใส่ไฟล์ใหม่
            if ($req->file('out_file')) {
                $file = $req->file('out_file'); // Retrieve the uploaded file from the request
                $filename = $uuid . "." . $file->getClientOriginalExtension(); // Retrieve the original filename

                $destination_path = "public/uploads/outDoc/";
                // Storage::disk('local')->put('uploads/userImgs/'.$filename, file_get_contents($file));
                $path = $file->storeAs($destination_path, $filename);
            } else {
                $filename = '';
            }

            $data['offsite_path'] = $filename;
        } else {
            $msg = "ไม่มีการลบไฟล์";

            // ถ้ามีการใส่ไฟล์ กรณีไม่มีไฟล์อยู่แล้ว
            if ($req->file('out_file')) {
                $file = $req->file('out_file'); // Retrieve the uploaded file from the request
                $filename = $uuid . "." . $file->getClientOriginalExtension(); // Retrieve the original filename

                $destination_path = "public/uploads/outDoc/";
                // Storage::disk('local')->put('uploads/userImgs/'.$filename, file_get_contents($file));
                $path = $file->storeAs($destination_path, $filename);
                $data['offsite_path'] = $filename;
            }
        }


        $now = Carbon::now();
        $data['offsite_start'] = $req->s_date;
        $data['offsite_end'] = $req->e_date;
        $data['offsite_place'] = $req->out_place;
        $data['offsite_direc'] = $req->out_direc;
        $data['offsite_desc'] = $req->out_desc;
        $data['status_form'] = 'Wait Progress';
        $data['to_manager'] = (int)$req->to_menager;
        $data['updated_at'] = $now;




        // Update data in the database
        $updatedRows = DB::table('offsite_works')
            ->where('offsite_id', $req->form_id)
            ->update($data);

        if ($updatedRows === 0) {
            return response()->json(['error' => "Update failed"]);
        }


        return response()->json(['success' => "Update Form Success", 'data' => $data, 'msg' => $msg]);
    }

    // Create
    function CreatedocForm()
    {
        $mangerInfo = DB::table('users')->where('department', 'Manager')->get();

        return view('components.fretrue.Form.createForm.docCreate', compact('mangerInfo'));
    }

    function CreatedocPost(Request $req)
    {

        $customMessages = [
            'type_doc.required' => 'กรุณาใส่ประเภทเอกสาร',
            'doc_lang.required' => 'กรุณาเลือกภาษา',

            'rec_form.required' => 'กรุณาเลือกรูปแบบการรับเอกสาร',
            'doc_amount.required' => 'กรุณาเลือกจำนวนเอกสาร',
            'doc_Y.required' => 'กรุณาเลือกปีของเอกสาร',
            'doc_M.required' => 'กรุณาเลือกเดือนของเอกสาร',
            'to_menager.required' => 'กรุณาเลือกหัวหน้าของคุณ'
            // Add more custom messages as needed
        ];

        $validator = Validator::make($req->all(), [
            'type_doc' => 'required',
            'doc_lang' => 'required',
            'rec_form' => 'required',
            'doc_amount' => 'required',
            'doc_Y' => 'required',
            'doc_M' => 'required',
            'to_menager' => 'required'

        ], $customMessages);


        if ($validator->fails()) {
            // Handle validation failure, return error response or redirect back with errors
            return response()->json(['error' => $validator->errors()]);
        }



        $now = Carbon::now();
        $data['doc_type'] = $req->type_doc;
        $data['doc_lang'] = $req->doc_lang;
        $data['doc_pick'] = $req->rec_form;
        $data['doc_amount'] = (int)$req->doc_amount;
        $data['doc_Y'] = (int)$req->doc_Y;
        $data['doc_M'] = $req->doc_M;
        $data['doc_desc'] = $req->doc_desc;
        $data['to_manager'] = (int)$req->to_menager;
        $data['create_by'] = auth()->user()->id;
        $data['status_form'] = 'Wait Progress';
        $data['created_at'] = $now;
        $data['updated_at'] = $now;

        // // Create a new instance of your model
        // $model = new reDocuments();

        // // Assign values from the request
        // $model->doc_type = $req->type_doc;
        // $model->doc_lang = $req->doc_lang;
        // $model->doc_pick = $req->rec_form;
        // $model->doc_amount = (int)$req->doc_amount;
        // $model->doc_Y = (int)$req->doc_Y;
        // $model->doc_M = $req->doc_M;
        // $model->doc_desc = $req->doc_desc;
        // $model->to_manager = (int)$req->to_menager;
        // $model->create_by = auth()->user()->id; // Assuming you have a logged-in user
        // $model->status_form = 'Wait Progress';
        // Save the model

        // $model->save()

        // DB
        $dataSave = DB::table('re_documents')->insert($data);

        if (!$dataSave) {
            // return redirect(route('/registration'))->with("error" , "Can't create value");
            return response()->json(['error' => "Create user fail"]);
        }
        return response()->json(['success' => 'Create user success', 'data' => $data]);
    }



    function CreateleaveForm()
    {
        $mangerInfo = DB::table('users')->where('department', 'Manager')->get();
        return view('components.fretrue.Form.createForm.leaveCreate',  compact('mangerInfo'));
    }


    function CreateleavePost(Request $req)
    {

        $uuid = Str::uuid();
        $customMessages = [
            'type_leave.required' => 'กรุณาใส่ประเภทการลา',
            's_date.required' => 'กรุณาเลือกวันเริ่มต้นลา',

            'e_date.required' => 'กรุณาเลือกวันสุดท้ายที่ลา',
            'leave_desc.required' => 'กรุณาใส่เหตุผลการลา',
            'to_menager.required' => 'กรุณาเลือกหัวหน้าของคุณ'

            // Add more custom messages as needed
        ];

        $validator = Validator::make($req->all(), [
            'type_leave' => 'required',
            's_date' => 'required',
            'e_date' => 'required',
            'leave_desc' => 'required',
            'to_menager' => 'required'

        ], $customMessages);


        if ($validator->fails()) {
            // Handle validation failure, return error response or redirect back with errors
            return response()->json(['error' => $validator->errors()]);
        }



        // Store the file in a public directory

        if ($req->file('leave_file')) {
            $file = $req->file('leave_file'); // Retrieve the uploaded file from the request
            $filename = $uuid . "." . $file->getClientOriginalExtension(); // Retrieve the original filename

            $destination_path = "public/uploads/leaveDoc/";
            // Storage::disk('local')->put('uploads/userImgs/'.$filename, file_get_contents($file));
            $path = $file->storeAs($destination_path, $filename);
        } else {
            $filename = '';
        }




        $now = Carbon::now();
        $data['absence_type'] = $req->type_leave;
        $data['absence_start'] = $req->s_date;
        $data['absence_end'] = $req->e_date;
        $data['absence_desc'] = $req->leave_desc;
        $data['absence_path'] = $filename;
        $data['status_form'] = 'Wait Progress';
        $data['to_manager'] = (int)$req->to_menager;
        $data['create_by'] = auth()->user()->id;
        $data['created_at'] = $now;
        $data['updated_at'] = $now;


        // // DB
        $dataSave = DB::table('absences')->insert($data);

        if (!$dataSave) {
            // return redirect(route('/registration'))->with("error" , "Can't create value");
            return response()->json(['error' => "Create user fail"]);
        }
        return response()->json(['success' => 'Create user success', 'data' => $filename]);
    }


    function CreateoutForm()
    {
        $mangerInfo = DB::table('users')->where('department', 'Manager')->get();
        return view('components.fretrue.Form.createForm.outCreate', compact('mangerInfo'));
    }

    function CreateoutPost(Request $req)
    {

        $uuid = Str::uuid();
        $customMessages = [

            's_date.required' => 'กรุณาเลือกวันเริ่มต้น',
            'e_date.required' => 'กรุณาเลือกวันสุดท้าย',
            'out_place.required' => 'กรุณาใส่ชื่อสถานที่',
            'out_direc.required' => 'กรุณาใส่ที่อยู่',
            'out_desc.required' => 'กรุณาใส่รายละเอียด',
            'to_menager.required' => 'กรุณาเลือกหัวหน้าของคุณ'
            // Add more custom messages as needed
        ];

        $validator = Validator::make($req->all(), [
            's_date' => 'required',
            'e_date' => 'required',
            'out_place' => 'required',
            'out_direc' => 'required',
            'out_desc' => 'required',
            'to_menager' => 'required'

        ], $customMessages);


        if ($validator->fails()) {
            // Handle validation failure, return error response or redirect back with errors
            return response()->json(['error' => $validator->errors()]);
        }



        // Store the file in a public directory

        if ($req->file('out_file')) {
            $file = $req->file('out_file'); // Retrieve the uploaded file from the request
            $filename = $uuid . "." . $file->getClientOriginalExtension(); // Retrieve the original filename

            $destination_path = "public/uploads/outDoc/";
            // Storage::disk('local')->put('uploads/userImgs/'.$filename, file_get_contents($file));
            $path = $file->storeAs($destination_path, $filename);
        } else {
            $filename = '';
        }




        $now = Carbon::now();
        $data['offsite_start'] = $req->s_date;
        $data['offsite_end'] = $req->e_date;
        $data['offsite_place'] = $req->out_place;
        $data['offsite_direc'] = $req->out_direc;
        $data['offsite_desc'] = $req->out_desc;
        $data['offsite_path'] = $filename;
        $data['status_form'] = 'Wait Progress';
        $data['to_manager'] = (int)$req->to_menager;
        $data['create_by'] = auth()->user()->id;
        $data['created_at'] = $now;
        $data['updated_at'] = $now;


        // // DB
        $dataSave = DB::table('offsite_works')->insert($data);

        if (!$dataSave) {
            // return redirect(route('/registration'))->with("error" , "Can't create value");
            return response()->json(['error' => "Create Form fail"]);
        }
        return response()->json(['success' => 'Create Form success', 'data' => $filename]);
    }


    // Delete

    function DeleteDoc($id){

        $deleted = DB::table('re_documents')->where('doc_id', $id)->delete();

        if ($deleted) {
            return response()->json(['success' => 'Document deleted successfully']);
        } else {
            return response()->json(['error' => 'Document not found'], 404);
        }
    }

    function DeleteLeave($id){


        $checkImg = DB::table('absences')->where('absence_id', $id)->get();


        if($checkImg[0]->absence_path){
         
            $fileName = $checkImg[0]->absence_path;
            $path = 'uploads/leaveDoc/' . $fileName;

            // ลบไฟล์เก่า
            if (Storage::disk('public')->exists($path)) {
                // Delete the file
                Storage::disk('public')->delete($path);
                $msg = "File deleted successfully.";
            } else {
                $msg = "File does not exist.";
            }

        }else {
            $msg ="ไม่มีรูปภาพ";
        }


        $deleted = DB::table('absences')->where('absence_id', $id)->delete();

        if ($deleted) {
            return response()->json(['success' => 'Delete form success' , 'data' => $checkImg[0]->absence_path , 'msg' => $msg]);
        } else {
            return response()->json(['error' => 'Document not found'], 404);
        }


        
    }

    function DeleteOut($id){

        $checkImg = DB::table('offsite_works')->where('offsite_id', $id)->get();


        if($checkImg[0]->offsite_path){
         
            $fileName = $checkImg[0]->offsite_path;
            $path = 'uploads/outDoc/' . $fileName;

            // ลบไฟล์เก่า
            if (Storage::disk('public')->exists($path)) {
                // Delete the file
                Storage::disk('public')->delete($path);
                $msg = "File deleted successfully.";
            } else {
                $msg = "File does not exist.";
            }

        }else {
            $msg ="ไม่มีรูปภาพ";
        }

        
        $deleted = DB::table('offsite_works')->where('offsite_id', $id)->delete();

        if ($deleted) {
            return response()->json(['success' => 'Delete form success' , 'form_id' => $id , 'msg' => $msg ]);
        } else {
            return response()->json(['error' => 'Document not found'], 404);
        }

       
    }


}
