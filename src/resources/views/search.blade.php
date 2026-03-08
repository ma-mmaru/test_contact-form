{{-- 検索 --}}

<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>検索</title>
    <link rel="stylesheet" href="{{ asset('css/sanitize.css') }}" />
    <link rel="stylesheet" href="{{ asset('css/admin.css') }}">
</head>

<body>
    <header class="header">
        <div class="header__inner">
            <a class="header__logo">
                FashionablyLate
            </a>
            <div class="logout__link">
                <a class="logout__button-submit" href="/logout">logout</a>
            </div>
        </div>
    </header>
    <main>
        <div class="search-form__content">
            <div class="search-form__heading">
                <h2>Admin</h2>
            </div>
            <form action="/search" method="get" class="search-form">
                {{-- 名前とメールアドレスを1つの検索欄にする --}}
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
                <button class='reset_button' type="button" onclick="location.href='/admin'">リセット</button>
            </form>

            {{-- ページネーションのリンク表示 --}}
            <div class=" pagination">
                {{ $contacts->links() }}
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
                    @foreach ($contacts as $contact)
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
                            <a class="detail-button" href="#">詳細</a>
                        </td>
                    </tr>
                    @endforeach
                </table>
            </div>
        </div>
    </main>
</body>

</html>