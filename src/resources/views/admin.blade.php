{{-- 管理画面 --}}
<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>管理画面</title>
    <link rel="stylesheet" href="{{ asset('css/sanitize.css') }}" />
    <link rel="stylesheet" href="{{ asset('css/admin.css') }}">
</head>

<body>
    <header class=" header">
        <div class="header__inner">
            <a class="header__logo">
                FashionablyLate
            </a>
            <div class="logout__link">
                <form action="/logout" method="post">
                    @csrf
                    <button type="submit" class="logout__button-submit">logout</button>
                </form>
            </div>
        </div>
    </header>
    <main>
        <div class="admin-form__content">
            <div class="admin-form__heading">
                <h2>Admin</h2>
            </div>
            <form action="/search" method="get" class="search-form">
                {{-- 名前とメールアドレスを1つの検索窓に統合 --}}
                <input type="text" name="nameEmail" placeholder="名前やメールアドレスを入力してください"
                    value="{{ request('nameEmail') }}">
                {{-- 性別選択 --}}
                <select name="gender">
                    <option value="all" {{ request('gender') == 'all' ? 'selected' : '' }}>性別</option>
                    <option value="1" {{ request('gender') == '1' ? 'selected' : '' }}>男性</option>
                    <option value="2" {{ request('gender') == '2' ? 'selected' : '' }}>女性</option>
                    <option value="3" {{ request('gender') == '3' ? 'selected' : '' }}>その他</option>
                </select>
                {{-- お問い合わせの種類 --}}
                <select name="category_id">
                    <option value="">お問い合わせの種類</option>
                    @foreach($categories as $category)
                    <option value="{{ $category->id }}" {{ request('category_id') == $category->id ? 'selected' : '' }}>
                        {{ $category->content }}
                    </option>
                    @endforeach
                </select>
                {{-- 日付 --}}
                <input type="date" name="date" value="{{ request('date') }}">
                <button class='search__button' type="submit">検索</button>
                <button class='reset_button' type="button" onclick="location.href='/reset'">リセット</button>
            </form>
            <div class="admin-form__options">
                {{-- エクスポートボタン --}}
                <form action="/export" method="get">
                    @foreach(request()->query() as $key => $value)
                    @if($key !== 'page')
                    <input type="hidden" name="{{ $key }}" value="{{ $value }}">
                    @endif
                    @endforeach
                    <button class="export__button" type="submit">エクスポート</button>
                </form>
                {{-- ページネーションのリンク表示 --}}
                <nav class="pagination">
                    {{ $contacts->appends(request()->query())->links('pagination::bootstrap-4') }}
                </nav>
            </div>
            {{-- お問い合わせ一覧テーブル --}}
            <div class="admin-table">
                <table class="admin-table__inner">
                    <tr class="admin-table__row">
                        <th class="admin-table__header">お名前</th>
                        <th class="admin-table__header">性別</th>
                        <th class="admin-table__header">メールアドレス</th>
                        <th class="admin-table__header">お問い合わせの種類</th>
                        <th class="admin-table__header"></th> {{-- 詳細ボタン用 --}}
                    </tr>
                    @foreach($contacts as $contact)
                    <tr class="admin-table__row">
                        <td class="admin-table__item">
                            {{ $contact->last_name }} {{ $contact->first_name }}
                        </td>
                        <td class="admin-table__item">
                            @if($contact->gender == 1) 男性
                            @elseif($contact->gender == 2) 女性
                            @else その他 @endif
                        </td>
                        <td class="admin-table__item">
                            {{ $contact->email }}
                        </td>
                        <td class="admin-table__item">
                            {{ $contact->category->content }}
                        </td>
                        <td class="admin-table__item">
                            {{-- 詳細表示用のボタン(モーダル) --}}
                            <a class="detail-button" href="#modal-{{ $contact->id }}">詳細</a>
                        </td>
                    </tr>
                    @endforeach
                </table>
                {{-- モーダルウィンドウ --}}
                @foreach($contacts as $contact)
                <div class="modal" id="modal-{{ $contact->id }}">
                    <a href="#!" class="modal-overlay"></a> {{-- 背景（クリックで閉じる用） --}}
                    <div class="modal__inner">
                        {{-- 右上の「×」マーク（href="#!" でアンカーを解除して閉じる） --}}
                        <a href="#!" class="modal-close">×</a>
                        <div class="modal__content">
                            <table class="modal-detail-table">
                                <tr>
                                    <th>お名前</th>
                                    <td>{{ $contact->last_name }} {{ $contact->first_name }}</td>
                                </tr>
                                <tr>
                                    <th>性別</th>
                                    <td>
                                        @if($contact->gender == 1) 男性
                                        @elseif($contact->gender == 2) 女性
                                        @else その他 @endif
                                    </td>
                                </tr>
                                <tr>
                                    <th>メールアドレス</th>
                                    <td>{{ $contact->email }}</td>
                                </tr>
                                <tr>
                                    <th>電話番号</th>
                                    <td>{{ $contact->tel }}</td>
                                </tr>
                                <tr>
                                    <th>住所</th>
                                    <td>{{ $contact->address }}</td>
                                </tr>
                                <tr>
                                    <th>建物名</th>
                                    <td>{{ $contact->building }}</td>
                                </tr>
                                <tr>
                                    <th>お問い合わせの種類</th>
                                    <td>{{ $contact->category->content }}</td>
                                </tr>
                                <tr>
                                    <th>お問い合わせ内容</th>
                                    <td>{{ nl2br(e($contact->detail)) }}</td>
                                </tr>
                            </table>
                            {{-- 削除ボタン --}}
                            <form class="delete-form" action="/delete" method="post">
                                @method('delete')
                                @csrf
                                <input type="hidden" name="id" value="{{ $contact->id }}">
                                <button class="delete-button" type="submit">削除</button>
                            </form>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </main>
</body>

</html>