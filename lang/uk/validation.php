<?php 

return [
    'accepted' => 'Поле :attribute повинно бути прийнято.',
    'accepted_if' => 'Поле :attribute повинно бути прийнято, якщо :other дорівнює :value.',
    'active_url' => 'Поле :attribute має невірний URL.',
    'after' => 'Поле :attribute повинно бути датою після :date.',
    'after_or_equal' => 'Поле :attribute повинно бути датою після або дорівнюватися :date.',
    'alpha' => 'Поле :attribute може містити лише букви.',
    'alpha_dash' => 'Поле :attribute може містити лише букви, цифри, тире і підкреслення.',
    'alpha_num' => 'Поле :attribute може містити лише букви і цифри.',
    'array' => 'Поле :attribute повинно бути масивом.',
    'before' => 'Поле :attribute повинно бути датою перед :date.',
    'before_or_equal' => 'Поле :attribute повинно бути датою перед або дорівнюватися :date.',
    'between' => [
        'array' => 'Поле :attribute повинно містити від :min до :max елементів.',
        'file' => 'Поле :attribute повинно бути в файлі від :min до :max кілобайт.',
        'numeric' => 'Поле :attribute повинно бути в діапазоні від :min до :max.',
        'string' => 'Поле :attribute повинно містити від :min до :max символів.',
    ],
    'boolean' => 'Поле :attribute повинно мати значення true або false.',
    'confirmed' => 'Підтвердження :attribute не збігається.',
    'current_password' => 'Поточний пароль невірний.',
    'date' => 'Поле :attribute не є дійсною датою.',
    'date_equals' => 'Поле :attribute повинно бути датою, рівною :date.',
    'date_format' => 'Поле :attribute не відповідає формату :format.',
    'declined' => 'Поле :attribute повинно бути відхилене.',
    'declined_if' => 'Поле :attribute повинно бути відхилене, коли :other дорівнює :value.',
    'different' => 'Поля :attribute і :other повинні бути різними.',
    'digits' => 'Поле :attribute повинно мати :digits цифри.',
    'digits_between' => 'Поле :attribute повинно бути в діапазоні від :min до :max цифр.',
    'dimensions' => 'Поле :attribute має невірні розміри зображення.',
    'distinct' => 'Поле :attribute має дубльоване значення.',
    'email' => 'Поле :attribute повинно бути дійсною адресою електронної пошти.',
    'ends_with' => 'Поле :attribute повинно закінчуватися одним із наступних значень: :values.',
    'enum' => 'Обраний :attribute невірний.',
    'exists' => 'Обраний :attribute невірний.',
    'file' => 'Поле :attribute повинно бути файлом.',
    'filled' => 'Поле :attribute повинно мати значення.',
    'gt' => [
        'array' => 'Поле :attribute повинно містити більше елементів, ніж :value.',
        'file' => 'Поле :attribute повинно бути більше, ніж :value кілобайт.',
        'numeric' => 'Поле :attribute повинно бути більше, ніж :value.',
        'string' => 'Поле :attribute повинно бути більше, ніж :value символів.',
    ],
    'gte' => [
        'array' => 'Поле :attribute повинно містити :value елементів або більше.',
        'file' => 'Поле :attribute повинно бути більше або дорівнювати :value кілобайт.',
        'numeric' => 'Поле :attribute повинно бути більше або дорівнювати :value.',
        'string' => 'Поле :attribute повинно бути більше або дорівнювати :value символів.',
    ],
    'image' => 'Поле :attribute повинно бути зображенням.',
    'in' => 'Обраний :attribute невірний.',
    'in_array' => 'Поле :attribute не існує в :other.',
    'integer' => 'Поле :attribute повинно бути цілим числом.',
    'ip' => 'Поле :attribute повинно бути дійсною IP-адресою.',
    'ipv4' => 'Поле :attribute повинно бути дійсною IPv4-адресою.',
    'ipv6' => 'Поле :attribute повинно бути дійсною адресою IPv6.',
    'json' => 'Поле :attribute повинно бути правильним JSON-рядком.',
    'lt' => [
        'array' => 'Поле :attribute повинно мати менше ніж :value позицій.',
        'file' => 'Поле :attribute повинно бути менше ніж :value кілобайт.',
        'numeric' => 'Поле :attribute повинно бути менше ніж :value.',
        'string' => 'Поле :attribute повинно бути менше ніж :value символів.',
    ],
    'lte' => [
        'array' => 'Поле :attribute не може містити більше ніж :value позицій.',
        'file' => 'Поле :attribute повинно бути менше або рівним :value кілобайт.',
        'numeric' => 'Поле :attribute повинно бути менше або рівним :value.',
        'string' => 'Поле :attribute повинно бути менше або рівним :value символів.',
    ],
    'mac_address' => 'Поле :attribute повинно бути правильною MAC-адресою.',
    'max' => [
        'array' => 'Поле :attribute не може містити більше ніж :max елементів.',
        'file' => 'Поле :attribute не може бути більшим ніж :max кілобайт.',
        'numeric' => 'Поле :attribute не може бути більшим ніж :max.',
        'string' => 'Поле :attribute не може бути більшим ніж :max символів.',
    ],
    'mimes' => 'Поле :attribute повинно бути файлом одного з наступних типів: :values.',
    'mimetypes' => 'Поле :attribute повинно бути файлом одного з наступних типів: :values.',
    'min' => [
        'numeric' => 'Поле :attribute повинно бути не менше :min.',
        'file' => 'Поле :attribute повинно бути не менше :min кілобайт.',
        'string' => 'Поле :attribute повинно містити не менше :min символів.',
        'array' => 'Поле :attribute повинно містити не менше :min позицій.',
    ],
    'multiple_of' => 'Поле :attribute повинно бути кратним :value.',
    'not_in' => 'Обраний :attribute є недійсним.',
    'not_regex' => 'Формат :attribute недійсний.',
    'numeric' => 'Поле :attribute повинно бути числовим.',
    'password' => [
        'letters' => 'Поле :attribute повинно містити щонайменше одну літеру.',
        'mixed' => 'Поле :attribute повинно містити щонайменше одну велику і одну малу літеру.',
        'numbers' => 'Поле :attribute повинно містити щонайменше одну цифру.',
        'symbols' => 'Поле :attribute повинно містити щонайменше один символ.',
        'uncompromised' => 'Вказаний :attribute вже з\'являвся в результаті витоку даних. Виберіть інший :attribute.',
    ],
    'present' => 'Поле :attribute повинно бути присутнім.',
    'prohibited' => 'Поле :attribute заборонене.',
    'prohibited_if' => 'Поле :attribute заборонене, коли :other дорівнює :value',
    'prohibited_unless' => 'Поле :attribute заборонене, якщо :other не знаходиться в :values',
    'prohibits' => 'Поле :attribute перешкоджає наявності :other',
    'regex' => 'Формат :attribute недійсний.',
    'required' => 'Поле :attribute є обов\'язковим.',
    'required_array_keys' => 'Поле :attribute повинно містити записи для: :values.',
    'required_if' => 'Поле :attribute є обов\'язковим, коли :other дорівнює :value.',
    'required_unless' => 'Поле :attribute є обов\'язковим, якщо :other не знаходиться в :values.',
    'required_with' => 'Поле :attribute є обов\'язковим, коли присутній :values.',
    'required_with_all' => 'Поле :attribute є обов\'язковим, коли присутній :values.',
    'required_without' => 'Поле :attribute є обов\'язковим, коли відсутній :values.',
    'required_without_all' => 'Поле :attribute є обов\'язковим, коли відсутній жодний з :values.',
    'same' => 'Поле :attribute та :other повинні бути ідентичними.',
    'size' => [
        'array' => 'Поле :attribute повинно містити :size елементи.',
        'file' => 'Поле :attribute повинно бути розміром :size кілобайт.',
        'numeric' => 'Поле :attribute повинно бути розміром :size.',
        'string' => 'Поле :attribute повинно бути розміром :size символів.',
    ],
    'starts_with' => 'Поле :attribute повинно починатися з одного з наступних: :values',
    'string' => 'Поле :attribute повинно бути рядком.',
    'timezone' => 'Поле :attribute повинно бути дійсною часовою зоною.',
    'unique' => 'Поле :attribute вже існує.',
    'uploaded' => 'Завантаження :attribute не вдалося',
    'url' => 'Формат :attribute недійсний.',
    'uuid' => 'Поле :attribute повинно бути дійсним UUID.',
    'captcha' => 'Поле :attribute недійсне.',
    'recaptcha' => 'Поле :attribute недійсне.',
    'phone' => 'Поле :attribute містить недійсний номер.',
    'phone_number' => 'Your phone number is invalid.',
    'required_package_id' => 'To continue, you must select the premium auction option.',
    'required_payment_method_id' => 'To continue, you must select a payment method.',
    'blacklist_unique' => 'The value of the :attribute field is already blocked for :type.',
    'blacklist_email_rule' => 'This email address is blacklisted.',
    'blacklist_phone_rule' => 'This phone number is blacklisted.',
    'blacklist_domain_rule' => 'The domain of your email address is blacklisted.',
    'blacklist_ip_rule' => 'This :attribute must be a valid IP address.',
    'blacklist_word_rule' => 'This :attribute contains prohibited words or phrases.',
    'blacklist_title_rule' => 'This :attribute contains prohibited words or phrases.',
    'between_rule' => 'This :attribute must be between :min and :max characters.',
    'username_is_valid_rule' => 'This :attribute must be an alphanumeric string.',
    'username_is_allowed_rule' => 'This :attribute is not allowed.',
    'locale_of_language_rule' => 'This :attribute is invalid.',
    'locale_of_country_rule' => 'This :attribute is invalid.',
    'currencies_codes_are_valid_rule' => 'This :attribute is invalid.',
    'custom_field_unique_rule' => 'The :field_1 field is already assigned to this :field_2.',
    'custom_field_unique_rule_field' => 'The :field_1 field is already assigned to this :field_2.',
    'custom_field_unique_children_rule' => 'The child :field_1 of the :field_1 field is already assigned to this :field_2.',
    'custom_field_unique_children_rule_field' => 'The :field_1 field is already assigned to one of the :field_2 within this :field_2.',
    'custom_field_unique_parent_rule' => 'The parent :field_1 of the :field_1 field is already assigned to this :field_2.',
    'custom_field_unique_parent_rule_field' => 'The :field_1 field is already assigned to the parent item :field_2 within this :field_2.',
    'mb_alphanumeric_rule' => 'Please enter valid content in the :attribute field.',
    'date_is_valid_rule' => 'The :attribute field does not contain a valid date.',
    'date_future_is_valid_rule' => 'The date in the :attribute field must be in the future.',
    'date_past_is_valid_rule' => 'The date in the :attribute field must be in the past.',
    'video_link_is_valid_rule' => 'The :attribute field does not contain a valid video link (Youtube or Vimeo).',
    'sluggable_rule' => 'The :attribute field contains only invalid characters.',
    'uniqueness_of_listing_rule' => 'You have already published this listing. It cannot be duplicated.',
    'uniqueness_of_unverified_listing_rule' => 'You have already published this listing. Please check your email or SMS for verification instructions.',
    'custom' => [
        'database_connection' => [
            'required' => 'Не вдалося підключитися до сервера MySQL.',
        ],
        'database_not_empty' => [
            'required' => 'База даних не порожня. Будь ласка, очистіть базу даних або вкажіть <a href="./database">іншу базу даних</a>.',
        ],
        'promo_code_not_valid' => [
            'required' => 'Промокод недійсний.',
        ],
        'smtp_valid' => [
            'required' => 'Не вдалося підключитися до сервера SMTP.',
        ],
        'yaml_parse_error' => [
            'required' => 'Неможливо проаналізувати YAML. Перевірте синтаксис.',
        ],
        'file_not_found' => [
            'required' => 'Файл не знайдено.',
        ],
        'not_zip_archive' => [
            'required' => 'Файл не є архівом ZIP.',
        ],
        'zip_archive_unvalid' => [
            'required' => 'Неможливо прочитати архів.',
        ],
        'custom_criteria_empty' => [
            'required' => 'Нестандартні критерії не можуть бути порожніми.',
        ],
        'php_bin_path_invalid' => [
            'required' => 'Недійсний виконуваний файл PHP. Будь ласка, перевірте ще раз.',
        ],
        'can_not_empty_database' => [
            'required' => 'Деякі таблиці не можуть бути видалені. Будь ласка, вручну очистіть базу даних і спробуйте ще раз.',
        ],
        'can_not_create_database_tables' => [
            'required' => 'Деякі таблиці не можуть бути створені. Переконайтеся, що у вас є повні права на базу даних і спробуйте ще раз.',
        ],
        'can_not_import_database_data' => [
            'required' => 'Неможливо імпортувати всі необхідні дані додатка. Будь ласка, спробуйте ще раз.',
        ],
        'recaptcha_invalid' => [
            'required' => 'Недійсний тест reCAPTCHA.',
        ],
        'payment_method_not_valid' => [
            'required' => 'Щось пішло не так з налаштуванням способу оплати. Будь ласка, перевірте ще раз.',
        ],
        'attribute-name' => [
            'rule-name' => 'custom-message',
        ],
    ],
    'attributes' => [
        'gender' => 'стать',
        'gender_id' => 'стать',
        'name' => 'назва',
        'first_name' => 'ім\'я',
        'last_name' => 'прізвище',
        'user_type' => 'тип користувача',
        'user_type_id' => 'тип користувача',
        'country' => 'країна',
        'country_code' => 'країна',
        'phone' => 'телефон',
        'address' => 'адреса',
        'mobile' => 'мобільний',
        'sex' => 'стать',
        'year' => 'рік',
        'month' => 'місяць',
        'day' => 'день',
        'hour' => 'година',
        'minute' => 'хвилина',
        'second' => 'секунда',
        'username' => 'ім\'я користувача',
        'email' => 'адреса електронної пошти',
        'password' => 'пароль',
        'password_confirmation' => 'підтвердження паролю',
        'g-recaptcha-response' => 'капча',
        'accept_terms' => 'умови',
        'category' => 'Категорія',
        'category_id' => 'Категорія',
        'post_type' => 'тип запису',
        'post_type_id' => 'тип запису',
        'title' => 'заголовок',
        'body' => 'тіло',
        'description' => 'опис',
        'excerpt' => 'витримка',
        'date' => 'дата',
        'time' => 'час',
        'available' => 'доступний',
        'size' => 'розмір',
        'price' => 'ціна',
        'salary' => 'заробітна плата',
        'contact_name' => 'назва',
        'location' => 'Місцезнаходження',
        'admin_code' => 'Місцезнаходження',
        'city' => 'місто',
        'city_id' => 'місто',
        'package' => 'пакет',
        'package_id' => 'пакет',
        'payment_method' => 'спосіб оплати',
        'payment_method_id' => 'спосіб оплати',
        'sender_name' => 'назва',
        'subject' => 'тема',
        'message' => 'повідомлення',
        'report_type' => 'тип звіту',
        'report_type_id' => 'тип звіту',
        'file' => 'файл',
        'filename' => 'Назва файлу',
        'picture' => 'зображення',
        'resume' => 'резюме',
        'login' => 'Увійти',
        'code' => 'код',
        'token' => 'знак',
        'comment' => 'коментар',
        'rating' => 'рейтинг',
        'locale' => 'локалізація',
        'currencies' => 'валюти',
        'tags' => 'Теги',
        'from_name' => 'назва',
        'from_email' => 'електронна пошта',
        'from_phone' => 'телефон',
        'captcha' => 'код безпеки',
    ],
    'ascii' => 'The :attribute field must only contain single-byte alphanumeric characters and symbols.',
    'can' => 'The :attribute field contains an unauthorized value.',
    'decimal' => 'The :attribute field must have :decimal decimal places.',
    'doesnt_end_with' => 'The :attribute field must not end with one of the following: :values.',
    'doesnt_start_with' => 'The :attribute field must not start with one of the following: :values.',
    'lowercase' => 'The :attribute field must be lowercase.',
    'max_digits' => 'The :attribute field must not have more than :max digits.',
    'min_digits' => 'The :attribute field must have at least :min digits.',
    'missing' => 'The :attribute field must be missing.',
    'missing_if' => 'The :attribute field must be missing when :other is :value.',
    'missing_unless' => 'The :attribute field must be missing unless :other is :value.',
    'missing_with' => 'The :attribute field must be missing when :values is present.',
    'missing_with_all' => 'The :attribute field must be missing when :values are present.',
    'required_if_accepted' => 'The :attribute field is required when :other is accepted.',
    'uppercase' => 'The :attribute field must be uppercase.',
    'ulid' => 'The :attribute field must be a valid ULID.',
];
