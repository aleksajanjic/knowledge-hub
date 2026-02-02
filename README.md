# Knowledge Hub

## 1. Project Description

**Knowledge Hub** is a Q&A platform similar to Stack Overflow, built with Laravel. Users can ask questions, provide answers, vote, and use AI for automatic answer generation.

**Main features:**
- Questions and answers with Markdown support
- Voting system (upvote/downvote) for questions and answers
- User reputation
- Tags and categories for content organization
- Accept best answer
- **Bookmarks** – save favorite questions and filter by "My Bookmarks"
- **Activity log** – tracking events (create/update/delete questions and answers)
- **AI integration** – automatic answer generation (OpenAI, Gemini, Anthropic, OpenRouter)
- Admin panel for managing users, tags, and categories
- Audit log for AI requests (tokens, providers, status)

---

## 2. Requirements

- **PHP** 8.2 or newer
- **Composer** 2.x
- **Node.js** 18+ and npm
- **MySQL** 8.0 (or SQLite for local development)
- **PHP extensions:** BCMath, Ctype, cURL, DOM, Fileinfo, JSON, Mbstring, OpenSSL, PCRE, PDO, Tokenizer, XML

---

## 3. Installation Steps

### With Docker

```bash
cd knowledge-hub
docker compose up -d --build
docker compose exec app composer install
docker compose exec app cp .env.example .env
docker compose exec app php artisan key:generate
docker compose exec app php artisan migrate:fresh --force
docker compose exec app php artisan db:seed
cd app && npm install && npm run build
```

Application: **http://localhost:8080**  
phpMyAdmin: **http://localhost:8081**

> **Note:** If you get the error "Please provide a valid cache path", run:
> `docker compose exec app mkdir -p storage/framework/{cache/data,sessions,views} storage/logs bootstrap/cache && docker compose exec app chmod -R 775 storage bootstrap/cache`
> or rebuild the image: `docker compose up -d --build`

Users seeded:

1. username: admin@mail.com, password: admin
2. username: moderator@mail.com, password: moderator
3. username: member@mail.com, password: member

### Without Docker (local)

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

## 4. Environment Configuration

### Basic Variables (.env)

| Variable | Description |
|----------|-------------|
| `APP_NAME` | Application name |
| `APP_ENV` | `local` / `production` |
| `APP_DEBUG` | `true` / `false` |
| `APP_URL` | Application URL (e.g. `http://localhost:8080`) |
| `DB_CONNECTION` | `mysql` (Docker) or `sqlite` |
| `DB_HOST` | `db` in Docker, `127.0.0.1` locally without Docker |
| `DB_DATABASE` | Database name (`knowledge_hub`) |
| `DB_USERNAME` | Database user (`user` in Docker) |
| `DB_PASSWORD` | Database password (`password` in Docker) |

### AI Variables (optional)

| Variable | Description |
|----------|-------------|
| `AI_ENABLED` | `true` to enable AI features |
| `AI_AUTO_ANSWER` | `true` for automatic answers to new questions |
| `AI_PRIMARY_PROVIDER` | `openai`, `gemini`, `anthropic` or `openrouter` |
| `OPENAI_API_KEY` | API key for OpenAI |
| `OPENAI_MODEL` | Model (e.g. `gpt-4o-mini`) |
| `GEMINI_API_KEY` | API key for Google Gemini |
| `GEMINI_MODEL` | Model (e.g. `gemini-2.0-flash`) |
| `ANTHROPIC_API_KEY` | API key for Anthropic Claude |
| `OPENROUTER_API_KEY` | API key for OpenRouter (unified access to multiple providers) |
| `OPENROUTER_MODEL` | Model (e.g. `openai/gpt-4o-mini`) |

---

## 5. Database Setup

```bash
# Migrations
php artisan migrate

# Seeding (users, questions, categories)
php artisan db:seed

# All at once
php artisan migrate:fresh --seed
```

---

## 6. API Documentation

The application uses JSON responses for AJAX requests. Main endpoints:

### POST `/questions/{question}/answers`

Add an answer to a question.

**Request:**
```json
{
  "content": "Your answer here (min. 10 characters)"
}
```

**Response (success):**
```json
{
  "success": true,
  "answer_id": 42
}
```

### POST `/questions/{question}/upvote` / `/questions/{question}/downvote`

Vote on a question.

**Response:**
```json
{
  "votes": 5,
  "userVote": 1,
  "authorReputation": 150
}
```

### POST `/answers/{answer}/upvote` / `/answers/{answer}/downvote`

Vote on an answer.

**Response:**
```json
{
  "votes": 3,
  "userVote": 1,
  "authorReputation": 120
}
```

### POST `/ai/questions/{question}/generate-answer`

Generate AI answer (requires authentication).

**Response (success):**
```json
{
  "success": true,
  "message": "AI answer generated successfully!",
  "answer_id": 42
}
```

### GET `/questions/{question}/details`

HTML content for question modal (AJAX).

### POST `/questions/{question}/bookmark`

Add/remove bookmark on a question (toggle).

**Response:**
```json
{
  "bookmarked": true
}
```

---

## 7. Architectural Decisions

| Decision | Explanation |
|----------|-------------|
| **Laravel Breeze** | Simple authentication without complex packages |
| **Polymorphic votes** | Single `votes` table for votes on questions and answers (`votable_type`, `votable_id`) |
| **Morph map** | Short `votable_type` values (`Question`, `Answer`) instead of full class names |
| **AIManager + providers** | Abstract layer (`AIServiceInterface`) for multiple AI providers with fallback |
| **AutoAnswerService** | Centralized logic for AI answer generation and audit log |
| **QuestionObserver** | Automatic AI answer generation when creating questions (if `AI_AUTO_ANSWER=true`) |
| **ReputationService** | Reputation calculation based on votes and accepted answers |
| **Spatie Permission** | Roles (admin, moderator, member) for access control |
| **Activity log** | `activity_log` table for questions, answers, and answer acceptance |
| **QuestionBookmark** | `question_bookmarks` table for saving favorite questions |

---

## 8. AI Integration

### Supported Providers

- **OpenAI** (GPT)
- **Google Gemini**
- **Anthropic** (Claude)
- **OpenRouter** (unified API for 100+ models)

### Configuration

1. Set `AI_ENABLED=true` in `.env`
2. Add an API key for at least one provider
3. Choose primary provider: `AI_PRIMARY_PROVIDER=openai` (or `gemini`, `anthropic`, `openrouter`)
4. Optional: `AI_AUTO_ANSWER=true` for automatic answers to new questions

### Testing

```bash
php artisan ai:test
# or with a question:
php artisan ai:test "What is Laravel?"
```

### Fallback

If the primary provider fails (quota, error), AIManager automatically uses the next in line: `openai` → `anthropic` → `gemini` → `openrouter`.

### Audit

All AI requests are logged in `ai_request_audits` (provider, model, prompt, response, tokens, status). Access: `/ai/dashboard` and `/ai/audit-logs`.

---

## 9. Known Limitations

- **AI provider quota** – free plans have limits; paid plans required for production
- **Documentation generation** – not implemented (AI only generates answers to questions)
- **Response streaming** – AI responses are returned at once, no streaming
- **Rate limiting** – no explicit rate limiting for AI endpoints
- **Caching** – responses for the same questions are not cached
- **Internationalization** – most text is in English; extensible via `lang/` files

---

## Additional Commands

```bash
# Clear cache
php artisan config:clear
php artisan cache:clear

# Create admin user (manually or via seeder)
php artisan tinker
```
