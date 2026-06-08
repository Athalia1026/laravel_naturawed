<?php
namespace App\Http\Controllers;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Crypt;

class ChatController extends Controller
{
public function index() {
      // 1. Ambil daftar percakapan (jika ada)
        $conversations = DB::table('conversations as c')
            ->leftJoin('users as u1', 'c.user_one', '=', 'u1.id')
            ->leftJoin('users as u2', 'c.user_two', '=', 'u2.id')
            ->where('c.user_one', Auth::id())->orWhere('c.user_two', Auth::id())
            ->select('c.*', 'u1.name as partner_one_name', 'u2.name as partner_two_name')
            ->orderBy('c.updated_at', 'desc')
            ->get();

        // 2. Jika punya riwayat chat, langsung arahkan ke chat paling baru
        if ($conversations->isNotEmpty()) {
            return redirect()->route('chat.show', $conversations->first()->id);
        }

        // 3. Jika benar-benar KOSONG, buka halaman index dan bawa variabel kosong agar tidak error
        return view('chat.index', compact('conversations'));
    }

public function send(Request $request) {
    DB::table('messages')->insert([
            'conversation_id' => $request->conversation_id,
            'sender_id' => Auth::id(),
            'message' => Crypt::encryptString($request->message),
            'created_at' => now()
        ]);
        return back();
    }

public function show($id) 
    {
        // 1. Ambil data percakapan untuk memastikan user yang akses adalah salah satu partisipan
        $conversation = DB::table('conversations')
            ->where('id', $id)
            ->where(function($q) {
                $q->where('user_one', Auth::id())
                  ->orWhere('user_two', Auth::id());
            })
            ->first();

        DB::table('messages')
            ->where('conversation_id', $id)
            ->where('sender_id', '!=', Auth::id()) // Hanya tandai pesan dari lawan bicara
            ->whereNull('read_at')                 // Yang belum dibaca
            ->update(['read_at' => now()]);

        if (!$conversation) {
            return abort(403, 'Unauthorized access.');
        }

        // 2. Ambil semua pesan dalam percakapan ini
     // Ambil semua percakapan user saat ini dengan join nama partner
    $conversations = DB::table('conversations as c')
        ->leftJoin('users as u1', 'c.user_one', '=', 'u1.id')
        ->leftJoin('users as u2', 'c.user_two', '=', 'u2.id')
        ->where('c.user_one', Auth::id())->orWhere('c.user_two', Auth::id())
        ->select('c.*', 'u1.name as partner_one_name', 'u2.name as partner_two_name')
        ->get();

    $messages = DB::table('messages')
        ->where('conversation_id', $id)
        ->orderBy('created_at', 'asc')
        ->get();

    foreach ($messages as $msg) {
            try {
                // Mencoba mendekripsi pesan
                $msg->message = Crypt::decryptString($msg->message);
            } catch (\Illuminate\Contracts\Encryption\DecryptException $e) {
                // Fallback: Jika ada pesan lama yang terlanjur tidak dienkripsi (plaintext),
                // atau kuncinya tidak cocok, biarkan saja atau beri tanda.
                // Karena database Anda saat ini masih plaintext, kita biarkan aslinya dulu.
                // Jika ingin ketat: $msg->message = '[Pesan Terenkripsi/Rusak]';
            }
        }
        
    return view('chat.show', compact('messages', 'conversations', 'id'));
    }   
    
public function start($vendor_id) {
        $vendor = \App\Models\User::find($vendor_id); // Asumsi vendor adalah User
       
       if (Auth::id() == $vendor_id) {
        return back()->with('error', 'Anda tidak bisa mengirim pesan ke toko Anda sendiri.');
     }

        $conversation = DB::table('conversations')
            ->where(function($q) use ($vendor_id) {
                $q->where('user_one', Auth::id())->where('user_two', $vendor_id);
            })
            ->orWhere(function($q) use ($vendor_id) {
                $q->where('user_one', $vendor_id)->where('user_two', Auth::id());
            })
            ->first();

        if (!$conversation) {
            $id = DB::table('conversations')->insertGetId([
                'user_one' => Auth::id(), 
                'user_two' => $vendor_id,
                'created_at' => now(),
                'updated_at' => now(),
                ]);
            return redirect()->route('chat.show', $id);
        }
        return redirect()->route('chat.show', $conversation->id);
    }
}