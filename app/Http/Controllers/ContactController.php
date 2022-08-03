<?php

namespace App\Http\Controllers;

use App\Mail\AdminMail;
use App\Models\Contact;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class ContactController extends Controller
{
    function show()
    {
        try {
            $contact_list = Contact::all();

            return response()->json([
                "contact_list" => $contact_list
            ])->withHeaders([
                'Access-Control-Allow-Origin' => '*'
            ]);
        } catch (Exception $ex) {
            $message = $ex->getMessage();
            return response()->json([
                "error" => $message
            ])->withHeaders([
                'Access-Control-Allow-Origin' => '*'
            ]);
        }
    }

    function store(Request $req)
    {
        try {
            $input = $req->input("contact_input");
            $name = $input["name"];
            $email = $input["email"];
            $body = $input["body"];

            //validation
            if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
            } else {
                return response()->json([
                    "success" => 0,
                    "error_message" => "正しいメールアドレスではありません",
                ])->withHeaders([
                    'Access-Control-Allow-Origin' => '*'
                ]);
            }

            if (
                mb_strlen($name)  > 30 ||
                mb_strlen($body) > 1000 ||
                mb_strlen($name) < 1 ||
                mb_strlen($body) < 1
            ) {
                return response()->json([
                    "success" => 0,
                    "error_message" => "名前は1～30文字、お問い合わせは1～1000文字で入力してください",
                ])->withHeaders([
                    'Access-Control-Allow-Origin' => '*'
                ]);
            }

            Contact::create([
                "name" => $name,
                "email" => $email,
                "body" => $body
            ]);

            Mail::to("piskjw4sw@icloud.com")->send(
                new AdminMail($name, $body, $email)
            );

            $contact_list = Contact::all();

            return response()->json([
                "contact_list" => $contact_list
            ])->withHeaders([
                'Access-Control-Allow-Origin' => '*'
            ]);
        } catch (Exception $ex) {
            $message = $ex->getMessage();
            return response()->json([
                "error" => $message
            ])->withHeaders([
                'Access-Control-Allow-Origin' => '*'
            ]);
        }
    }

    function delete(Request $req)
    {
        try {
            $id = $req->input("id");

            if (Contact::find($id)) {
                Contact::find($id)->delete();
            }

            $contact_list = Contact::all();

            return response()->json([
                "contact_list" => $contact_list
            ])->withHeaders([
                'Access-Control-Allow-Origin' => '*'
            ]);
        } catch (Exception $ex) {
            $message = $ex->getMessage();
            return response()->json([
                "error" => $message
            ])->withHeaders([
                'Access-Control-Allow-Origin' => '*'
            ]);
        }
    }

    function changeState(Request $req)
    {
        try {
            $id = $req->input("id");
            $newState = $req->input("state");

            if (Contact::find($id)) {
                $contact = Contact::find($id);
                $contact->state = $newState;
                $contact->save();
            }

            $contact_list = Contact::all();

            return response()->json([
                "contact_list" => $contact_list
            ])->withHeaders([
                'Access-Control-Allow-Origin' => '*'
            ]);
        } catch (Exception $ex) {
            $message = $ex->getMessage();
            return response()->json([
                "error" => $message
            ])->withHeaders([
                'Access-Control-Allow-Origin' => '*'
            ]);
        }
    }
}
