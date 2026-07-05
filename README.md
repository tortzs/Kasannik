# Kasannik - Pomocnik dla studenta

Kasannik to kompleksowy i intuicyjny system webowy stworzony z myślą o studentach, mający na celu efektywną organizację czasu i zarządzanie postępami w nauce. Głównym problemem, z którym zmagają się studenci, jest zarządzanie dużą ilością rozproszonych informacji, takich jak terminy kolokwiów, linki do kursów czy kontakty do prowadzących. Nasza aplikacja rozwiązuje ten problem, integrując najważniejsze narzędzia w jednym, spójnym miejscu.

## Główne funkcjonalności

* **Panel główny (Dashboard):** Szybki podgląd 3 najbliższych terminów zaliczeń, 3 zadań z listy To-Do oraz planu zajęć na nadchodzący dzień.
* **Zarządzanie planem zajęć:** Dodawanie i przeglądanie zajęć z uwzględnieniem dni tygodnia oraz parzystości (tydzień parzysty, nieparzysty, każdy).
* **Terminarz i zaliczenia:** Śledzenie dat egzaminów i projektów z automatyczną kategoryzacją kolorystyczną na podstawie czasu pozostałego do upływu terminu (np. "Po terminie", "Dzisiaj", "Za X dni").
* **Lista zadań (To-Do):** Interaktywna lista pozwalająca na dodawanie bieżących obowiązków i oznaczanie ich jako ukończone.
* **Struktura uczelniana:** Zarządzanie semestrami (w tym flagowanie aktywnego semestru) oraz przypisywanie do nich konkretnych przedmiotów.
* **Baza prowadzących:** Przechowywanie danych kontaktowych (e-mail, numer telefonu, pokój) wykładowców i przypisywanie ich do odpowiednich kursów.
* **Bezpieczeństwo i personalizacja:** System bezpiecznej rejestracji i logowania chroniony przed atakami CSRF dzięki tokenom sesyjnym, kryptograficznie zabezpieczone hasła oraz możliwość wgrania własnego awatara.

## Wykorzystane technologie i architektura

Projekt został zrealizowany w architekturze **MVC (Model-View-Controller)** połączonej z własnym, autorskim systemem routingu, co zapewnia doskonałe oddzielenie logiki biznesowej od warstwy prezentacji. W celu optymalizacji wydajności wykorzystano mechanizmy cache'owania dla widoków oraz klauzule LIMIT w zapytaniach generujących Dashboard, by zminimalizować obciążenie bazy.

* **Backend:** PHP 8.5
* **Frontend:** HTML5, CSS, Vanilla JavaScript
* **Baza danych:** MariaDB (połączenie z wykorzystaniem obiektu PDO)
* **Serwer:** Apache2

## Instalacja i uruchomienie lokalne

Aplikacja jest przystosowana do działania na serwerze z obsługą języka PHP oraz bazą danych MariaDB.

1. **Sklonuj repozytorium** do folderu publicznego swojego serwera.
2. **Przygotowanie bazy danych:**
    * Utwórz nową, pustą bazę danych w MariaDB.
    * Zaimportuj skrypt SQL dołączony do projektu.
3. **Konfiguracja środowiska (.env):**
    * W głównym katalogu projektu odszukaj plik o nazwie `.env_example` i utwórz jego kopię o nazwie `.env`.
    * Otwórz plik `.env` w edytorze tekstu i uzupełnij go danymi dostępowymi do Twojej bazy danych (adres serwera, nazwa utworzonej bazy, użytkownik, hasło).
4. **Uruchomienie:**
    * Upewnij się, że usługi serwera Apache oraz MariaDB są uruchomione.
    * Przejdź w przeglądarce pod adres lokalny projektu (domyślnie punktem wejścia jest `public/index.php`).

## 📖 Krótka instrukcja obsługi

Kasannik posiada responsywny interfejs oparty na czytelnym panelu bocznym. Większość operacji (CRUD) wykonuje się w pełni intuicyjnie bez konieczności jakiejkolwiek ingerencji w bazę danych z poziomu kodu.

1. **Rejestracja:** Przy pierwszym uruchomieniu stwórz konto użytkownika w formularzu rejestracji.
2. **Start semestru:** Przejdź do zakładki *Semestry*, stwórz nowy wpis określając ramy czasowe i koniecznie ustaw jego status jako **Aktywny**.
3. **Konfiguracja uczelniana:** Wprowadź listę swoich przedmiotów oraz uzupełnij zakładkę *Prowadzący*, co pozwoli Ci mieć błyskawiczny dostęp do linków, numerów sal i adresów e-mail.
4. **Zarządzanie czasem:** Używaj modułów *Plan Zajęć* i *Terminy*, aby zaplanować swój harmonogram. Do szybkiego zarządzania codziennymi "mikrozadaniami" wykorzystaj *Listę To-Do*.

## 👥 Autorzy

Projekt stanowiący dokumentację zaliczeniową dla przedmiotu *Programowanie obiektowe i graficzne* na kierunku Informatyka (Wydział Matematyki Stosowanej, Politechnika Śląska).

Zespół Front-End:
* Klaudia Chołda
* Piotr Ptak

Zespół Back-End:
* Mateusz Grabowski
* Szymon Mostowski