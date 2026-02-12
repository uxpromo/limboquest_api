# Implementation Plan: Quest → PricingRule связь

Branch: none
Created: 2026-02-12

## Цель

Убрать из модели Quest поля `players_base_limit`, `surcharge_price`, `base_price` и заменить их связью `pricing_rule_id` с моделью PricingRule. Изменения на бэкенде и фронтенде. Миграции правятся в существующих (fresh-migrate).

## Settings

- Testing: no
- Logging: no
- **PricingRule:** SoftDeletes (миграция + модель)
- **pricing_rule_id в БД:** nullable (защита от потери записи при удалении правила). **В формах на фронте** — поле обязательно

## Commit Plan

- **Commit 1** (после задач 1–5): "refactor(quest): replace pricing fields with pricing_rule_id relation"
- **Commit 2** (после задач 6–7): "refactor(admin): quest form uses PricingRuleSelector"

## Tasks

### Phase 1: Backend (API)

#### Task 1: Миграция quests — убрать поля, добавить pricing_rule_id

**Описание:**
В `database/migrations/2026_02_03_000327_create_quests_table.php`:

- Удалить строки: `players_base_limit`, `surcharge_price`, `base_price`
- Добавить: `$table->foreignId('pricing_rule_id')->nullable()->constrained('pricing_rules')->nullOnDelete();` (в БД nullable; в формах — обязательно)

Важно: таблица `pricing_rules` создаётся миграцией `2026_02_03_00112`, которая выполняется раньше (00112 < 000327).

**Файлы:** `database/migrations/2026_02_03_000327_create_quests_table.php`

**LOGGING:** Не требуется (миграция).

---

#### Task 2: Модель Quest — fillable и связь pricingRule

**Описание:**
В `app/Models/Quest.php`:

- Удалить из `$fillable`: `players_base_limit`, `surcharge_price`, `base_price`
- Добавить в `$fillable`: `pricing_rule_id`
- Добавить метод `pricingRule(): BelongsTo` → `return $this->belongsTo(PricingRule::class);`

**Файлы:** `app/Models/Quest.php`

**LOGGING:** Не требуется (модель).

---

#### Task 3: Модель PricingRule — обратная связь quests()

**Описание:**
В `app/Models/PricingRule.php`:

- Добавить `use Illuminate\Database\Eloquent\Relations\HasMany;`
- Добавить метод `quests(): HasMany` → `return $this->hasMany(Quest::class);`

**Файлы:** `app/Models/PricingRule.php`

**LOGGING:** Не требуется (модель).

---

#### Task 4: QuestStoreRequest и QuestUpdateRequest — валидация

**Описание:**
В обоих файлах:

- Удалить правила: `players_base_limit`, `surcharge_price`, `base_price`
- Добавить: `'pricing_rule_id' => ['required', 'integer', 'exists:pricing_rules,id']`

**Файлы:**

- `app/Http/Requests/V1/Admin/Quest/QuestStoreRequest.php`
- `app/Http/Requests/V1/Admin/Quest/QuestUpdateRequest.php`

**LOGGING:** Не требуется (валидация).

---

#### Task 5: QuestResource — pricing_rule_id и pricing_rule

**Описание:**
В `app/Http/Resources/V1/Admin/Quest/QuestResource.php`:

- Удалить: `players_base_limit`, `surcharge_price`, `base_price`
- Добавить: `'pricing_rule_id' => $this->pricing_rule_id`
- Добавить вложенный объект `'pricing_rule' => $this->whenLoaded('pricingRule', fn () => new PricingRuleResource($this->pricingRule))` (опционально, для удобства фронта)

Либо отдавать только `pricing_rule_id` — фронт может подгрузить правило отдельно. Для простоты: отдавать `pricing_rule_id` и при наличии `whenLoaded` — `pricing_rule`.

**Файлы:** `app/Http/Resources/V1/Admin/Quest/QuestResource.php`

**LOGGING:** Не требуется (Resource).

---

### Phase 2: Frontend (admin.limbo)

#### Task 6: Quest domain — types, model, draft

**Описание:**

- `Quest.types.ts`: удалить `players_base_limit`, `surcharge_price`, `base_price`; добавить `pricing_rule_id: number` (обязательное)
- `Quest.model.ts`: то же + в конструкторе
- `Quest.draft.ts`: то же + в конструкторе и `fromModel`

**Файлы:**

- `src/domains/quest/Quest.types.ts`
- `src/domains/quest/Quest.model.ts`
- `src/domains/quest/Quest.draft.ts`

**LOGGING:** Не требуется (domain).

---

#### Task 7: QuestForm — PricingRuleSelector вместо трёх полей

**Описание:**
В `src/components/quests/QuestForm.vue`:

- Удалить FFormItem для `base_price`, `surcharge_price`, `players_base_limit`
- Добавить FFormItem для `pricing_rule_id` с `PricingRuleSelector` (аналогично QuestSessionForm)
- В `rules`: удалить `base_price`, `surcharge_price`, `players_base_limit`; добавить правило для `pricing_rule_id` (required, число, существует в API)
- В `payload` внутри `validate()`: удалить три поля; добавить `pricing_rule_id: draft.pricing_rule_id` (обязательное)

Импорт: `import PricingRuleSelector from '../pricing_rules/PricingRuleSelector.vue'`

**Файлы:** `src/components/quests/QuestForm.vue`

**LOGGING:** Не требуется (компонент формы).

---

## Проверка

После выполнения:

1. `php artisan migrate:fresh` — успешно
2. Создание/редактирование квеста в админке — выбор правила цен вместо трёх полей
3. API GET/POST/PUT quests — `pricing_rule_id` вместо старых полей
