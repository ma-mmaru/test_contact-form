<?php

namespace App\Http\Controllers;

use App\Http\Requests\ContactRequest;
use Illuminate\Http\Request;
use App\Models\Contact;
use App\Models\Category;

class ContactController extends Controller
{
    public function index()
    {
        //DBからカテゴリー一覧を取得して入力画面へ
        $categories = Category::all();
        return view('index', compact('categories'));
    }

    public function confirm(ContactRequest $request)
    {
        //修正ボタンで戻った時用に入力情報をセッションに保存
        $request->flash();
        //電話番号を結合して変数$telに代入
        $tel = $request->tel1 . $request->tel2 . $request->tel3;
        //リクエストから必要な項目だけを取り出す
        $contact = $request->only([
            'last_name','first_name', 'gender', 'email', 'address', 'building', 'category_id', 'detail'
            ]);
        //取り出した配列に結合した'tel'を追加する
        $contact['tel'] = $tel;
        //選択されたIDを使ってCategoryモデルから名前を取得
        $category = Category::find($request->category_id);
        //カテゴリーが存在すればその名前をセット、なければ'不明'をセット
        $contact['category_content'] = $category ? $category->content : '不明';
        $contact['tel1'] = $request->tel1;
        $contact['tel2'] = $request->tel2;
        $contact['tel3'] = $request->tel3;
        return view('confirm', compact('contact'));
    }

    public function store(Request $request)
    {
        $contact = $request->only([
            'last_name', 'first_name', 'gender', 'email', 'tel', 'address', 'building', 'detail', 'category_id'
            ]);
        Contact::create($contact);
        return redirect()->route('thanks');
    }

    public function thanks()
    {
        return view('thanks');
    }

    public function back(Request $request)
    {
        return redirect('/')->withInput();
    }
}