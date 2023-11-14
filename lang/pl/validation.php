<?php 

return [
    'accepted' => 'Ten :attribute musi zostać zaakceptowany.',
    'accepted_if' => 'Ten :attribute musi być zaakceptowane, gdy :other to :value.',
    'active_url' => 'Ten :attribute nie jest prawidłowym adresem URL.',
    'after' => 'Ten :attribute musi być datą następującą po :date.',
    'after_or_equal' => 'Ten :attribute musi być datą późniejszą lub równą :date.',
    'alpha' => 'Ten :attribute może zawierać tylko litery.',
    'alpha_dash' => 'Ten :attribute może zawierać tylko litery, cyfry, myślniki i podkreślenia.',
    'alpha_num' => 'Ten :attribute może zawierać tylko litery i cyfry.',
    'array' => 'Ten :attribute musi być tablicą.',
    'before' => 'Ten  :attribute musi być datą przed :date.',
    'before_or_equal' => 'Ten  :attribute musi być datą wcześniejszą lub równą :date.',
    'between' => [
        'array' => 'Ten :attribute musi zawierać między :min a :max pozycji.',
        'file' => 'Ten :attribute musi zawierać się w przedziale od :min do :max kilobajtów.',
        'numeric' => 'Ten :attribute musi zawierać się w przedziale od :min do :max.',
        'string' => 'Ten :attribute musi zawierać się w przedziale od :min do :max znaków.',
    ],
    'boolean' => 'Ten :attribute musi mieć wartość true lub false.',
    'confirmed' => 'Potwierdzenie :attribute nie pasuje.',
    'current_password' => 'Hasło jest błędne.',
    'date' => 'Ten :attribute nie jest prawidłową datą.',
    'date_equals' => 'Ten :attribute musi być datą równą :date.',
    'date_format' => 'Ten :attribute nie pasuje do formatu :format.',
    'declined' => 'Ten :attribute musi zostać odrzucony.',
    'declined_if' => 'Ten :attribute musi zostać odrzucone, gdy :other to :value.',
    'different' => 'Ten :attribute i :other muszą być różne.',
    'digits' => 'Ten :attribute musi być :digits cyfrą.',
    'digits_between' => 'Ten :attribute musi zawierać się w przedziale od :min do :max cyfr.',
    'dimensions' => 'Ten :attribute ma nieprawidłowe wymiary obrazu.',
    'distinct' => 'Ten :attribute ma zduplikowaną wartość.',
    'email' => 'Ten :attribute musi być poprawnym adresem e-mail.',
    'ends_with' => 'Ten :attribute musi kończyć się jednym z następujących: :values.',
    'enum' => 'Wybrany :attribute jest nieprawidłowy.',
    'exists' => 'Wybrany :attribute jest nieprawidłowy.',
    'file' => 'Ten :attribute musi być plikiem.',
    'filled' => 'Ten :attribute musi mieć wartość.',
    'gt' => [
        'array' => 'Ten :attribute musi zawierać więcej elementów niż :value.',
        'file' => 'Ten :attribute musi być większe niż :value kilobajtów.',
        'numeric' => 'Ten :attribute musi być większe niż :value.',
        'string' => 'Ten :attribute musi być większe niż :value znaków.',
    ],
    'gte' => [
        'array' => 'Ten :attribute musi zawierać elementy :value lub więcej.',
        'file' => 'Ten :attribute musi być większe lub równe :value kilobajtów.',
        'numeric' => 'Ten :attribute musi być większe lub równe :value.',
        'string' => 'Ten :attribute musi być większe lub równe :value znaków.',
    ],
    'image' => 'Ten :attribute musi być obrazem.',
    'in' => 'Wybrany :atrybut jest nieprawidłowy.',
    'in_array' => 'Ten :attribute nie istnieje w :other.',
    'integer' => 'Ten :attribute musi być liczbą całkowitą.',
    'ip' => 'Ten :attribute musi być prawidłowym adresem IP.',
    'ipv4' => 'Ten :attribute musi być prawidłowym adresem IPv4.',
    'ipv6' => 'Ten :attribute musi być prawidłowym adresem IPv6.',
    'json' => 'Ten :attribute musi być poprawnym łańcuchem JSON.',
    'lt' => [
        'array' => 'Ten :attribute musi mieć mniej niż :value pozycji.',
        'file' => 'Ten :attribute musi być mniejsze niż :value kilobajtów.',
        'numeric' => 'Ten :attribute musi być mniejsze niż :value .',
        'string' => 'Ten :attribute musi być mniejsze niż :value znaków.',
    ],
    'lte' => [
        'array' => 'Ten :attribute nie może zawierać więcej niż :value pozycji.',
        'file' => 'Ten :attribute musi być mniejsze lub równe :value kilobajtów.',
        'numeric' => 'Ten :attribute musi być mniejsze lub równe :value .',
        'string' => 'Ten :attribute musi być mniejsze lub równe :value znaków.',
    ],
    'mac_address' => 'Ten :attribute musi być poprawnym adresem MAC.',
    'max' => [
        'array' => 'Ten :attribute nie może zawierać więcej niż :max elementów.',
        'file' => 'Ten :attribute nie może być większe niż :max kilobajtów.',
        'numeric' => 'Ten :attribute nie może być większy niż :max.',
        'string' => 'Ten :attribute nie może być większe niż :max znaków.',
    ],
    'mimes' => 'Ten :attribute musi być plikiem typu: :values.',
    'mimetypes' => 'Ten :attribute musi być plikiem typu: :values.',
    'min' => [
        'numeric' => 'Ten :attribute musi wynosić co najmniej :min.',
        'file' => 'Ten :attribute musi wynosić co najmniej :min kilobajtów.',
        'string' => 'Ten :attribute musi mieć co najmniej :min znaków.',
        'array' => 'Ten :attribute musi zawierać co najmniej :min pozycji.',
    ],
    'multiple_of' => 'Ten :attribute musi być wielokrotnością :value.',
    'not_in' => 'Wybrany :attribute jest nieprawidłowy.',
    'not_regex' => 'Format :attribute jest nieprawidłowy.',
    'numeric' => 'Ten :attribute musi być liczbą.',
    'password' => [
        'letters' => 'Ten :attribute musi zawierać co najmniej jedną literę.',
        'mixed' => 'Ten :attribute musi zawierać co najmniej jedną wielką i jedną małą literę.',
        'numbers' => 'Ten :attribute musi zawierać co najmniej jedną liczbę.',
        'symbols' => 'Ten :attribute musi zawierać co najmniej jeden symbol.',
        'uncompromised' => 'Podany :attribute pojawił się w wyniku wycieku danych. Wybierz inny :attribute.',
    ],
    'present' => 'Ten :attribute musi być obecne.',
    'prohibited' => 'Ten :attribute jest zabronione.',
    'prohibited_if' => 'Ten :attribute jest zabronione, gdy :other to :value',
    'prohibited_unless' => 'Ten :attribute jest zabronione, chyba że :other jest w :values',
    'prohibits' => 'Ten :attribute uniemożliwia obecność :other',
    'regex' => 'Format :attribute jest nieprawidłowy.',
    'required' => 'Ten :attribute jest wymagane.',
    'required_array_keys' => 'Ten :attribute musi zawierać wpisy dla: :values.',
    'required_if' => 'Ten :attribute jest wymagane, gdy :other to :value.',
    'required_unless' => 'Ten :attribute jest wymagane, chyba że :other jest w :values.',
    'required_with' => 'Ten :attribute jest wymagane, gdy obecne jest :values.',
    'required_with_all' => 'Ten :attribute jest wymagane, gdy obecne jest :values.',
    'required_without' => 'Ten :attribute jest wymagane, gdy nie ma :values.',
    'required_without_all' => 'Ten :attribute jest wymagane, gdy nie ma żadnych :values.',
    'same' => 'Ten :attribute i :other muszą być zgodne.',
    'size' => [
        'array' => 'Ten :attribute musi zawierać elementy :size.',
        'file' => 'Ten :attribute musi być :size kilobajty.',
        'numeric' => 'Ten :attribute musi być :size .',
        'string' => 'Ten :attribute musi mieć postać :size znaków.',
    ],
    'starts_with' => 'Ten :attribute musi zaczynać się od jednego z następujących: :values',
    'string' => 'Ten :attribute musi być łańcuchem.',
    'timezone' => 'Ten :attribute musi być prawidłową strefą.',
    'unique' => 'Ten :attribute został już zajęty.',
    'uploaded' => 'Przesłanie :attribute nie powiodło się',
    'url' => 'Format :attribute jest nieprawidłowy.',
    'uuid' => 'Ten :attribute musi być prawidłowym identyfikatorem UUID.',
    'captcha' => 'Ten :attribute jest nieprawidłowe.',
    'recaptcha' => 'Ten :attribute jest nieprawidłowe.',
    'phone' => 'Ten :attribute zawiera nieprawidłową liczbę.',
    'phone_number' => 'Twój numer telefonu jest nieprawidłowy',
    'required_package_id' => 'Aby kontynuować, musisz wybrać opcję aukcji premium',
    'required_payment_method_id' => 'Aby kontynuować, musisz wybrać metodę płatności',
    'blacklist_unique' => 'Wartość pola :attribute jest już zablokowana dla :type.',
    'blacklist_email_rule' => 'Ten adres e-mail znajduje się na czarnej liście',
    'blacklist_phone_rule' => 'Ten numer telefonu jest na czarnej liście',
    'blacklist_domain_rule' => 'Domena twojego adresu e-mail jest na czarnej liście',
    'blacklist_ip_rule' => 'Ten :attribute musi być prawidłowym adresem IP.',
    'blacklist_word_rule' => 'Ten :attribute zawiera zakazane słowa lub wyrażenia.',
    'blacklist_title_rule' => 'Ten :attribute zawiera zakazane słowa lub wyrażenia.',
    'between_rule' => 'Ten :attribute musi zawierać się w przedziale od :min do :max znaków.',
    'username_is_valid_rule' => 'Ten :attribute musi być łańcuchem alfanumerycznym.',
    'username_is_allowed_rule' => 'Ten :attribute nie jest dozwolony.',
    'locale_of_language_rule' => 'Ten :attribute jest nieprawidłowe.',
    'locale_of_country_rule' => 'Ten :attribute jest nieprawidłowe.',
    'currencies_codes_are_valid_rule' => 'Ten :attribute jest nieprawidłowe.',
    'custom_field_unique_rule' => 'Pole :field_1 ma już przypisane to :field_2',
    'custom_field_unique_rule_field' => 'Pole :field_1 jest już przypisane do tego :field_2.',
    'custom_field_unique_children_rule' => 'Dziecko :field_1 pola :field_1 ma już przypisane to :field_2',
    'custom_field_unique_children_rule_field' => 'Pole :field_1 jest już przypisane do jednego :field_2 z tego :field_2.',
    'custom_field_unique_parent_rule' => 'Rodzic :field_1 pola :field_1 ma już przypisane to :field_2',
    'custom_field_unique_parent_rule_field' => 'Pole :field_1 jest już przypisane do elementu nadrzędnego :field_2 tego :field_2.',
    'mb_alphanumeric_rule' => 'Wprowadź poprawną treść w polu :attribute.',
    'date_is_valid_rule' => 'Pole :attribute nie zawiera prawidłowej daty.',
    'date_future_is_valid_rule' => 'Data pola :attribute musi przypadać w przyszłości.',
    'date_past_is_valid_rule' => 'Data pola :attribute musi być w przeszłości.',
    'video_link_is_valid_rule' => 'Pole :attribute nie zawiera prawidłowego łącza wideo (Youtube lub Vimeo).',
    'sluggable_rule' => 'Pole :attribute zawiera tylko nieprawidłowe znaki.',
    'uniqueness_of_listing_rule' => 'Opublikowałeś to ogłoszenie. Nie można go powielić',
    'uniqueness_of_unverified_listing_rule' => 'Opublikowałeś to ogłoszenie. Sprawdź swój adres e-mail lub SMS, aby postępować zgodnie z instrukcjami dotyczącymi weryfikacji.',
    'custom' => [
        'database_connection' => [
            'required' => 'Nie można połączyć się z serwerem MySQL',
        ],
        'database_not_empty' => [
            'required' => 'Baza danych nie jest pusta. Proszę opróżnić bazę danych lub podać <a href="./database">another database</a>.',
        ],
        'promo_code_not_valid' => [
            'required' => 'Kod promocyjny jest nieprawidłowy',
        ],
        'smtp_valid' => [
            'required' => 'Nie można połączyć się z serwerem SMTP',
        ],
        'yaml_parse_error' => [
            'required' => 'Nie można analizować yaml. Sprawdź składnię',
        ],
        'file_not_found' => [
            'required' => 'Nie znaleziono pliku.',
        ],
        'not_zip_archive' => [
            'required' => 'Plik nie jest paczką ZIP',
        ],
        'zip_archive_unvalid' => [
            'required' => 'Nie można odczytać pakietu',
        ],
        'custom_criteria_empty' => [
            'required' => 'Kryteria niestandardowe nie mogą być puste',
        ],
        'php_bin_path_invalid' => [
            'required' => 'Nieprawidłowy plik wykonywalny PHP. Proszę Sprawdź ponownie.',
        ],
        'can_not_empty_database' => [
            'required' => 'Nie można USUNĄĆ niektórych tabel. Wyczyść ręcznie bazę danych i spróbuj ponownie.',
        ],
        'can_not_create_database_tables' => [
            'required' => 'Nie można utworzyć niektórych tabel. Upewnij się, że masz pełne uprawnienia do bazy danych i spróbuj ponownie.',
        ],
        'can_not_import_database_data' => [
            'required' => 'Nie można zaimportować wszystkich wymaganych danych aplikacji. Proszę spróbuj ponownie.',
        ],
        'recaptcha_invalid' => [
            'required' => 'Nieprawidłowy test reCAPTCHA',
        ],
        'payment_method_not_valid' => [
            'required' => 'Coś poszło nie tak z ustawieniem metody płatności. Proszę Sprawdź ponownie.',
        ],
        'attribute-name' => [
            'rule-name' => 'custom-message',
        ],
    ],
    'attributes' => [
        'gender' => 'płeć',
        'gender_id' => 'płeć',
        'name' => 'nazwa',
        'first_name' => 'imię',
        'last_name' => 'nazwisko',
        'user_type' => 'rodzaj użytkownika',
        'user_type_id' => 'rodzaj użytkownika',
        'country' => 'kraj',
        'country_code' => 'kraj',
        'phone' => 'telefon',
        'address' => 'adres',
        'mobile' => 'mobilny',
        'sex' => 'płeć',
        'year' => 'rok',
        'month' => 'miesiąc',
        'day' => 'dzień',
        'hour' => 'godzina',
        'minute' => 'minuta',
        'second' => 'drugi',
        'username' => 'nazwa użytkownika',
        'email' => 'adres e-mail',
        'password' => 'hasło',
        'password_confirmation' => 'Potwierdzenie hasła',
        'g-recaptcha-response' => 'captcha',
        'accept_terms' => 'warunki',
        'category' => 'Kategoria',
        'category_id' => 'Kategoria',
        'post_type' => 'typ postu',
        'post_type_id' => 'typ postu',
        'title' => 'tytuł',
        'body' => 'ciało',
        'description' => 'opis',
        'excerpt' => 'fragment',
        'date' => 'data',
        'time' => 'czas',
        'available' => 'dostępny',
        'size' => 'rozmiar',
        'price' => 'cena',
        'salary' => 'wynagrodzenie',
        'contact_name' => 'nazwa',
        'location' => 'Lokalizacja',
        'admin_code' => 'Lokalizacja',
        'city' => 'miasto',
        'city_id' => 'miasto',
        'package' => 'pakiet',
        'package_id' => 'pakiet',
        'payment_method' => 'metoda płatności',
        'payment_method_id' => 'metoda płatności',
        'sender_name' => 'nazwa',
        'subject' => 'temat',
        'message' => 'wiadomość',
        'report_type' => 'typ raportu',
        'report_type_id' => 'typ raportu',
        'file' => 'plik',
        'filename' => 'Nazwa pliku',
        'picture' => 'zdjęcie',
        'resume' => 'wznawiać',
        'login' => 'Zaloguj się',
        'code' => 'kod',
        'token' => 'znak',
        'comment' => 'komentarz',
        'rating' => 'ocena',
        'locale' => 'widownia',
        'currencies' => 'waluty',
        'tags' => 'Tagi',
        'from_name' => 'nazwa',
        'from_email' => 'e-mail',
        'from_phone' => 'telefon',
        'captcha' => 'kod bezpieczeństwa',
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