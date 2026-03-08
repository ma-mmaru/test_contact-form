<?php

namespace App\Http\Controllers;

use App\Models\Contact;
use App\Models\Category;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\StreamedResponse;

class AdminController extends Controller
{
    public function index(Request $request)
    {
        $query = Contact::query()->with('category');

        // 「名前・メールアドレス」検索欄に入力があった場合
        if ($request->filled('nameEmail')) {
            $nameEmail = $request->input('nameEmail');

            // whereの中で複数の条件をグループ化
            $query->where(function ($q) use ($nameEmail) {
                $q->where('last_name', 'like', '%' . $nameEmail . '%')
                    ->orWhere('first_name', 'like', '%' . $nameEmail . '%')
                    ->orWhere('email', 'like', '%' . $nameEmail . '%')
                    // 姓と名をくっつけた状態（フルネーム）でも検索
                    ->orWhereRaw('CONCAT(last_name, first_name) LIKE ?', ['%' . $nameEmail . '%']);
            });
        }

        // 性別
        if ($request->filled('gender') && $request->gender !== 'all') {
            $query->where('gender', $request->gender);
        }
        // お問い合わせの種類
        // $request->category_id が空でない場合のみ、そのIDで絞り込む
        if ($request->filled('category_id')) {
            $query->where('category_id', $request->category_id);
        }

        //　日付
        if ($request->filled('date')) {
            $query->whereDate('created_at', $request->date);
        }

        $contacts = $query->paginate(7)->appends($request->all());
        $categories = Category::all();

        return view('admin', compact('contacts', 'categories'));
    }

    public function reset()
    {
        return redirect('/admin');
    }

    public function destroy(Request $request)
    {
        Contact::find($request->id)->delete();

        return redirect('/admin');
    }

    // エクスポート機能
    public function export(Request $request)
    {
        //  検索条件を適用したクエリを作成
        $query = Contact::query()->with('category');

        if ($request->filled('nameEmail')) {
            $query->where(function ($q) use ($request) {
                $value = $request->input('nameEmail');
                $q->where('first_name', 'like', '%' . $value . '%')
                    ->orWhere('last_name', 'like', '%' . $value . '%')
                    ->orWhere('email', 'like', '%' . $value . '%');
            });
        }
        if ($request->filled('gender') && $request->input('gender') !== 'all') {
            $query->where('gender', $request->input('gender'));
        }

        // お問い合わせの種類検索
        if ($request->filled('category_id')) {
            $query->where('category_id', $request->input('category_id'));
        }

        // 日付検索（created_atの日付部分と一致させる）
        if ($request->filled('date')) {
            $query->whereDate('created_at', $request->input('date'));
        }
        $contacts = $query->get();

        // 2. CSVを生成してレスポンスとして返す
        return new StreamedResponse(function () use ($contacts) {
            $stream = fopen('php://output', 'w');

            // 文字化け防止（Excel対応：BOMを追加）
            fwrite($stream, "\xEF\xBB\xBF");

            // ヘッダー行
            fputcsv($stream, ['お名前', '性別', 'メールアドレス', 'お問い合わせの種類', '内容']);

            // データ行
            foreach ($contacts as $contact) {
                $gender = ($contact->gender == 1) ? '男性' : (($contact->gender == 2) ? '女性' : 'その他');

                fputcsv($stream, [
                    $contact->last_name . ' ' . $contact->first_name,
                    $gender,
                    $contact->email,
                    $contact->category->content ?? '削除済みカテゴリ',
                    $contact->detail,
                ]);
            }
            fclose($stream);
        }, 200, [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="contacts_' . date('YmdHis') . '.csv"',
            'Cache-Control' => 'no-cache, no-store, must-revalidate',
            'Pragma' => 'no-cache',
            'Expires' => '0',
        ]);
    }
}