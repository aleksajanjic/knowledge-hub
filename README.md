# Knowledge Hub

## 1. Opis projekta

**Knowledge Hub** je Q&A platforma slična Stack Overflow-u, izgrađena u Laravelu. Korisnici mogu postavljati pitanja, davati odgovore, glasati i koristiti AI za automatsko generisanje odgovora.

**Glavne funkcionalnosti:**
- Pitanja i odgovori sa Markdown podrškom
- Sistem glasanja (upvote/downvote) za pitanja i odgovore
- Reputacija korisnika
- Tagovi i kategorije za organizaciju sadržaja
- Prihvatanje najboljeg odgovora
- **AI integracija** – automatsko generisanje odgovora (OpenAI, Gemini, Anthropic, OpenRouter)
- Admin panel za upravljanje korisnicima, tagovima i kategorijama
- Audit log za AI zahteve (tokeni, provajderi, status)

---

## 2. Zahtevi

- **PHP** 8.2 ili noviji
- **Composer** 2.x
- **Node.js** 18+ i npm
- **MySQL** 8.0 (ili SQLite za lokalni razvoj)
- **PHP ekstenzije:** BCMath, Ctype, cURL, DOM, Fileinfo, JSON, Mbstring, OpenSSL, PCRE, PDO, Tokenizer, XML

---

## 3. Koraci instalacije

### Sa Docker-om

```bash
cd knowledge-hub
docker compose up -d
docker compose exec app composer install
docker compose exec app cp .env.example .env
docker compose exec app php artisan key:generate
docker compose exec app php artisan migrate --force
docker compose exec app php artisan db:seed
cd app && npm install && npm run build
```

Aplikacija: **http://localhost:8080**  
phpMyAdmin: **http://localhost:8081**

### Bez Docker-a (lokalno)

```bash
cd knowledge-hub/app
composer install
cp .env.example .env
php artisan key:generate
php artisan migrate
php artisan db:seed
npm install
npm run build
php artisan serve
```

---

## 4. Podešavanje okruženja

### Osnovne varijable (.env)

| Varijabla | Opis |
|-----------|------|
| `APP_NAME` | Ime aplikacije |
| `APP_ENV` | `local` / `production` |
| `APP_DEBUG` | `true` / `false` |
| `APP_URL` | URL aplikacije (npr. `http://localhost:8080`) |
| `DB_CONNECTION` | `mysql` ili `sqlite` |
| `DB_HOST` | Host baze (npr. `db` u Docker-u) |
| `DB_DATABASE` | Ime baze (`knowledge_hub`) |
| `DB_USERNAME` | Korisnik baze |
| `DB_PASSWORD` | Lozinka baze |

### AI varijable (opciono)

| Varijabla | Opis |
|-----------|------|
| `AI_ENABLED` | `true` za uključivanje AI funkcionalnosti |
| `AI_AUTO_ANSWER` | `true` za automatsko odgovaranje na nova pitanja |
| `AI_PRIMARY_PROVIDER` | `openai`, `gemini`, `anthropic` ili `openrouter` |
| `OPENAI_API_KEY` | API ključ za OpenAI |
| `OPENAI_MODEL` | Model (npr. `gpt-4o-mini`) |
| `GEMINI_API_KEY` | API ključ za Google Gemini |
| `GEMINI_MODEL` | Model (npr. `gemini-2.0-flash`) |
| `ANTHROPIC_API_KEY` | API ključ za Anthropic Claude |
| `OPENROUTER_API_KEY` | API ključ za OpenRouter (jedinstveni pristup više provajdera) |
| `OPENROUTER_MODEL` | Model (npr. `openai/gpt-4o-mini`) |

---

## 5. Podešavanje baze

```bash
# Migracije
php artisan migrate

# Seedovanje (korisnici, pitanja, kategorije)
php artisan db:seed

# Sve odjednom
php artisan migrate:fresh --seed
```

---

## 6. API dokumentacija

Aplikacija koristi JSON odgovore za AJAX zahteve. Glavni endpoint-i:

### POST `/questions/{question}/answers`

Dodavanje odgovora na pitanje.

**Zahtev:**
```json
{
  "content": "Vaš odgovor ovde (min. 10 karaktera)"
}
```

**Odgovor (uspeh):**
```json
{
  "success": true,
  "answer_id": 42
}
```

### POST `/questions/{question}/upvote` / `/questions/{question}/downvote`

Glasanje za pitanje.

**Odgovor:**
```json
{
  "votes": 5,
  "userVote": 1,
  "authorReputation": 150
}
```

### POST `/answers/{answer}/upvote` / `/answers/{answer}/downvote`

Glasanje za odgovor.

**Odgovor:**
```json
{
  "votes": 3,
  "userVote": 1,
  "authorReputation": 120
}
```

### POST `/ai/questions/{question}/generate-answer`

Generisanje AI odgovora (zahteva autentifikaciju).

**Odgovor (uspeh):**
```json
{
  "success": true,
  "message": "AI answer generated successfully!",
  "answer_id": 42
}
```

### GET `/questions/{question}/details`

HTML sadržaj pitanja za modal (AJAX).

---

## 7. Arhitekturalne odluke

| Odluka | Objašnjenje |
|--------|--------------|
| **Laravel Breeze** | Jednostavna autentifikacija bez potrebe za kompleksnim paketima |
| **Polimorfni glasovi** | Jedna `votes` tabela za glasove na pitanjima i odgovorima (`votable_type`, `votable_id`) |
| **Morph map** | Kratke `votable_type` vrednosti (`Question`, `Answer`) umesto punih imena klasa |
| **AIManager + provajderi** | Apstraktni sloj (`AIServiceInterface`) za više AI provajdera sa fallback-om |
| **AutoAnswerService** | Centralizovana logika za generisanje AI odgovora i audit log |
| **QuestionObserver** | Automatsko generisanje AI odgovora pri kreiranju pitanja (ako je `AI_AUTO_ANSWER=true`) |
| **ReputationService** | Izračunavanje reputacije na osnovu glasova i prihvaćenih odgovora |
| **Spatie Permission** | Uloge (admin, moderator, member) za kontrolu pristupa |

---

## 8. AI integracija

### Podržani provajderi

- **OpenAI** (GPT)
- **Google Gemini**
- **Anthropic** (Claude)
- **OpenRouter** (unified API za 100+ modela)

### Konfiguracija

1. Postavite `AI_ENABLED=true` u `.env`
2. Dodajte API ključ za bar jedan provajder
3. Izaberite primarnog provajdera: `AI_PRIMARY_PROVIDER=openai` (ili `gemini`, `anthropic`, `openrouter`)
4. Opciono: `AI_AUTO_ANSWER=true` za automatsko odgovaranje na nova pitanja

### Testiranje

```bash
php artisan ai:test
# ili sa pitanjem:
php artisan ai:test "Šta je Laravel?"
```

### Fallback

Ako primarni provajder ne uspe (kvota, greška), AIManager automatski koristi sledeće u nizu: `openai` → `anthropic` → `gemini` → `openrouter`.

### Audit

Svi AI zahtevi se beleže u `ai_request_audits` (provajder, model, prompt, odgovor, tokeni, status). Pristup: `/ai/dashboard` i `/ai/audit-logs`.

---

## 9. Poznata ograničenja

- **Kvota AI provajdera** – besplatni planovi imaju ograničenja; za produkciju je potrebno plaćanje
- **Generisanje dokumentacije** – nije implementirano (AI generiše samo odgovore na pitanja)
- **Streaming odgovora** – AI odgovori se vraćaju odjednom, bez streaming-a
- **Rate limiting** – nema eksplicitnog rate limiting-a za AI endpoint-e
- **Caching** – odgovori za ista pitanja se ne keširaju
- **Internacionalizacija** – većina teksta je na engleskom; moguće proširenje sa `lang/` fajlovima

---

## Dodatne komande

```bash
# Brisanje keša
php artisan config:clear
php artisan cache:clear

# Kreiranje admin korisnika (ručno ili preko seedera)
php artisan tinker
```
