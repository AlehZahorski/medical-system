# 🩺 Medical System – Laravel + Vue + Docker + CI/CD

Projekt rekrutacyjny dla Alab zawierający backend w Laravel 12 oraz frontend w Vue 3 (Vite),
uruchamiany lokalnie przy użyciu Docker Compose oraz zautomatyzowany w GitLab CI/CD.

---

## 📚 Zawartość Repozytorium

- `backend/` – aplikacja Laravel 12 (API, migracje, testy, seedery)
- `frontend/` – aplikacja Vue 3 + Vite
- `.gitlab-ci.yml` – konfiguracja CI/CD
- `docker-compose.yml` – uruchamianie aplikacji lokalnie (backend, frontend, MySQL, phpMyAdmin)
- `README.md` – instrukcja uruchomienia

---

## 🚀 Uruchomienie lokalne (Docker)

### 🔧 Wymagania

- Docker + Docker Compose
- Porty 8000, 5173, 3307 i 8081 muszą być wolne

### 🛠️ Kroki

a) Sklonuj repozytorium:

- git clone https://github.com/AlehZahorski/medical-system.git
- cd medical-system

b) Skopiuj plik `.env`:

cp backend/.env.example backend/.env

c) Uruchom środowisko:

docker-compose up --build

d) W innym terminalu: uruchom migracje i seedery:

docker exec -it laravel-app php artisan migrate:fresh --seed

e) Utwórz link symboliczny do katalogu storage (wymagane przez Laravel):

docker exec -it laravel-app php artisan storage:link

---

## 📁 Import danych z pliku CSV

Aby zaimportować dane pacjenta, zamówień i badań:

1. Umieść plik CSV (bez nagłówków) w katalogu:

   backend/storage/app/imports/basic/

2. Nazwa pliku nie może zawierać rozszerzenia w komendzie.

3. Uruchom import:

   docker exec -it laravel-app php artisan import:basic-patient-data {nazwa_pliku_bez_csv}

   Przykład:
   docker exec -it laravel-app php artisan import:basic-patient-data testowy_plik

---

## 🌐 Dostęp do aplikacji

| Usługa           | Adres                      |
|------------------|-----------------------------|
| API (backend)    | http://localhost:8000       |
| Frontend         | http://localhost:5173       |
| Admin Panel      | http://localhost:8000/admin |
| phpMyAdmin       | http://localhost:8081       |

---

## 🔐 Panel administratora (FilamentPHP)

W projekcie zintegrowano panel admina oparty o **FilamentPHP**.

Dane logowania:

Login:  admin@alab.pl  
Hasło:  Alab123!

---

## ✅ Testy backendu (PHPUnit)

Uruchomienie testów:

docker exec -it laravel-app php artisan test

---

## ⚙️ CI/CD (GitLab Pipelines)

Plik `.gitlab-ci.yml` zawiera:

a) Testy backendu – `php artisan test`  
b) Budowanie frontendu – `npm run build`  
c) Docker build & push – opcjonalne wypychanie obrazu

---

## 🧪 Baza danych w CI/CD

- Nazwa: `testing_db`
- Użytkownik: `root`
- Hasło: `root`

---

## 📂 Struktura katalogów

- backend/           # Laravel 12 (API)
- frontend/          # Vue 3 + Vite
- docker-compose.yml
- .gitlab-ci.yml
- README.md
- README_AZ.md

---

## 📦 Obraz Docker (opcjonalnie)

Budowanie i wypychanie:

docker build -t my-app ./backend  
docker tag my-app registry.gitlab.com/username/project-name  
docker push registry.gitlab.com/username/project-name

---

## 👤 Autor

**Aleh Zahorski**  
Zadanie wykonane w ramach procesu rekrutacyjnego.  
Czas realizacji: ~8h

---

Dziękuję za możliwość wykonania zadania! 
Nie udało mi się lokalnie uruchomić gitlab proces, 
przewaznie pracowałem i obecnie pracuję z bitbucket, 
dlatego konfuguracja ci/cd wyglada nie co inaczej dla mnie na co dzień, 
ale podejrzewam ze z gitlab tak samo nie ma nic trudnego :)

PS: wyglada sensownie ta konfiguracja co mi chat podał, ale nie ręczę się za to!

PS2: testy zrobiłem jakie potrafie, spec oraz unit, na froncie testów nigdy nie robiłem, 
ale kojarzę mniej więcej jak to wygląda.

PS3: oczywiście nie ukrywam bo moim zdaniem nie ma co, 
korzystałem z gpt dla generowania niektorych mechanizmów, w tym też częściowo dokumentacji :)

PS4: Oczywiście za całą reszte się ręczam!