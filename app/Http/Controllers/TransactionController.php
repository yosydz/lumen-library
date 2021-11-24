<?php

namespace App\Http\Controllers;
use App\Models\Transaction;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

use App\Models\Transaction;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class TransactionController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    // TODO: Create transaction logic

    public function index(){
        $transactions = Transaction::all();
        if ($transactions){
            if(count($transactions) != 0){
                return response()->json([
                'success' => true,
                'message' => 'List all transactions',
                'data' => ([
                    'transaction' => $transactions
                ])
                ], 201);
            }else{
                return response()->json([
                'success' => false,
                'message' => 'No transaction',
            ], 400);
            }
        }else{
            return response()->json([
                'success' => false,
                'message' => 'Server Failure',
            ], 500);
        }
    }

    public function getTransactionById($transactionId){
        $transactions = Transaction::find($transactionId);
        if ($transactions){
            if(!empty($transactions)){
                return response()->json([
                'success' => true,
                'message' => 'Get transaction by Id',
                'data' => ([
                    'transactions' => $transactions
                ])
                ], 201);
            }elseif(empty($transactions)){
                return response()->json([
                'success' => false,
                'message' => 'there is no transaction with id = '.$transactionId,
            ], 400);
            }
        }else{
            return response()->json([
                'success' => false,
                'message' => 'Server Failure',
            ], 500);
        }
    }

    public function postTransaction (Request $request){
        $book_id = $request->input('book_id');
        $user_id = $request->auth->id;
        $deadline = $request->input('deadline');
        
        // var_dump($request->auth->id);die;
        $validator = Validator::make($request->all(), [
            'book_id' => 'required',
            'deadline' => 'required'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => $validator->errors(),
            ], 400);
        }
      
        $postTransaction = Transaction::create([
            'book_id' => $book_id,
            'user_id' => $user_id,
            'deadline' => $deadline
        ]);

        if ($postTransaction) {
            return response()->json([
                'success' => true,
                'message' => 'Data Transaction Created Successfully!',
                'data' => ([
                    'transaction' => $postTransaction
                ])
            ], 201);
        } else {
            return response()->json([
                'success' => false,
                'message' => 'Request Failed!',
            ], 400);
        }

        
    }

    public function updateTransaction(Request $request, $transactionId){
        $updateTransaction = Transaction::findOrFail($transactionId);

        $validator = Validator::make($request->all(), [
            'deadline' => 'required'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => $validator->errors(),
            ], 400);
        }

        try {

            // $updateTransaction = Transaction::create([
            //     'deadline' => NULL
            // ]);

            $updateTransaction->update($request->all());
            $response = [
                'success' => true,
                'message' => 'Transaction Data Updated',
                'data' => ([
                    'user' => $updateTransaction
                ])
            ];
            return response()->json($response, 200);
        } catch (QueryException $error) {
            return response()->json([
                'success' => false,
                'message' => "Gagal" . $error->errorInfo,
            ], 400);
        }
    }
}
