{{-- お問い合わせフォーム確認ページ --}}

<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>お問い合わせフォーム確認ページ</title>
    <link rel="stylesheet" href="{{ asset('css/sanitize.css') }}" />
    <link rel="stylesheet" href="{{ asset('css/confirm.css') }}" />
</head>

<body>
    <header class="header">
        <div class="header__inner">
            <a class="header__logo">
                FashionablyLate
            </a>
        </div>
    </header>
    <main>
        <div class="confirm__content">
            <div class="confirm__heading">
                <h2>Confirm</h2>
            </div>
            <form class="form" action="/thanks" method="post">
                @csrf
                <div class="confirm-table">
                    <table class="confirm-table__inner">
                        <tr class="confirm-table__row">
                            <th class="confirm-table__header">お名前</th>
                            <td class="confirm-table__text">
                                {{-- 表示用 --}}
                                {{ $contact['last_name'] }} {{ $contact['first_name'] }}
                                {{-- 保存用 --}}
                                <input type="hidden" name="last_name" value="{{ $contact['last_name'] }}" />
                                <input type="hidden" name="first_name" value="{{ $contact['first_name'] }}" />
                            </td>
                        </tr>
                        <tr class="confirm-table__row">
                            <th class="confirm-table__header">性別</th>
                            <td class="confirm-table__text">
                                @php
                                $genderLabels =[1 => '男性', 2 => '女性', 3 => 'その他'];
                                @endphp
                                {{-- 数字に対応する文字を表示 --}}
                                {{ $genderLabels[$contact['gender']] ?? '未選択' }}
                                {{-- 保存用に数字をhiddenで送る --}}
                                <input type="hidden" name="gender" value="{{ $contact['gender'] }}" />
                            </td>
                        </tr>
                        <tr class="confirm-table__row">
                            <th class="confirm-table__header">メールアドレス</th>
                            <td class="confirm-table__text">
                                {{-- 表示用 --}}
                                {{ $contact['email'] }}
                                {{-- 保存用 --}}
                                <input type="hidden" name="email" value="{{ $contact['email'] }}" />
                            </td>
                        </tr>
                        <tr class="confirm-table__row">
                            <th class="confirm-table__header">電話番号</th>
                            <td class="confirm-table__text">
                                {{-- 表示用 --}}
                                {{ $contact['tel']}}
                                {{-- 保存用 --}}
                                <input type="hidden" name="tel" value="{{ $contact['tel'] }}" />
                            </td>
                        </tr>
                        <tr class="confirm-table__row">
                            <th class="confirm-table__header">住所</th>
                            <td class="confirm-table__text">
                                {{-- 表示用 --}}
                                {{ $contact['address'] }}
                                {{-- 保存用 --}}
                                <input type="hidden" name="address" value="{{ $contact['address'] }}" />
                            </td>
                        </tr>
                        <tr class="confirm-table__row">
                            <th class="confirm-table__header">建物名</th>
                            <td class="confirm-table__text">
                                {{-- 表示用 --}}
                                {{ $contact['building'] }}
                                {{-- 保存用 --}}
                                <input type="hidden" name="building" value="{{ $contact['building'] }}" />
                            </td>
                        </tr>
                        <tr class="confirm-table__row">
                            <th class="confirm-table__header">お問い合わせの種類</th>
                            <td class="confirm-table__text">
                                {{-- 表示用 --}}
                                {{ $contact['category_content'] }}
                                {{-- 保存用 --}}
                                <input type="hidden" name="category_id" value="{{ $contact['category_id'] }}"
                                    readonly />
                            </td>
                        </tr>
                        <tr class="confirm-table__row">
                            <th class="confirm-table__header">お問い合わせ内容</th>
                            <td class="confirm-table__text">
                                {{-- 表示用 --}}
                                {{ $contact['detail'] }}
                                {{-- 保存用 --}}
                                <input type="hidden" name="detail" value="{{ $contact['detail'] }}" />
                            </td>
                        </tr>
                    </table>
                </div>
                <div class="confirm__button">
                    <button class="confirm__button-submit" type="submit">送信</button>
                    <a href="/" class="back-button">修正</a>
                </div>
            </form>
        </div>
    </main>
</body>

</html>