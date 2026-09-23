# סבתא על הספסל — תבנית WordPress

תבנית עמוד-נחיתה (One-page) בעברית, RTL, שנבנתה ידנית לפי עיצוב ה-handoff ולפי **תקן הפיתוח של Multi Digital**:
ללא בוני עמודים, ללא jQuery וללא ספריות צד ג'; מאובטחת, רזה, נגישה (WCAG 2.2 AA / ת"י 5568), ידידותית ל-LiteSpeed Cache / Redis / Cloudflare ותואמת PHP 8.1–8.4.

## התקנה

1. להעתיק את התיקייה ל-`wp-content/themes/savta` (או להעלות כ-ZIP מ-"מראה ← ערכות עיצוב").
2. להפעיל את התבנית. בהפעלה נוצר אוטומטית עמוד **"הצהרת נגישות"** (`/accessibility-statement/`) עם טקסט בסיסי — יש למלא בו את פרטי רכז/ת הנגישות (מסומנים בסוגריים מרובעים).
3. מומלץ: **הגדרות ← כללי ← שפת האתר: עברית**. גם בלי זה ה-HTML נפלט עם `lang="he" dir="rtl"`.
4. **מראה ← התאמה אישית ← "סבתא על הספסל"**:
   - כתובת/ות דוא"ל לקבלת פניות (ברירת מחדל: דוא"ל מנהל האתר).
   - תמונת "הסבתא הראשונה שלנו" (סעיף 05) — עד שתועלה מוצג מקום שמור מעוצב.
5. אפשר להגדיר תפריט למיקום "תפריט ראשי" — אם לא מוגדר, מוצגים קישורי העוגן של העיצוב.

## מבנה

| קובץ | תפקיד |
| --- | --- |
| `front-page.php` + `template-parts/*.php` | כל סעיפי העמוד (Hero, פתיח, 01–08, פוטר) |
| `inc/content.php` | כל הטקסטים והרשימות (שלבים, קהל יעד, משפטים, שאלות) — ניתנים לדריסה ב-filters (`savta_steps`, `savta_faqs` …) |
| `inc/form.php` | טופס התיאום: Nonce, Honeypot, Rate limiting, ולידציה וסניטציה מלאה, שמירה ושליחת מייל |
| `inc/leads.php` | סוג תוכן פרטי `savta_lead` ("פניות") — נראה למנהלים בלבד (`manage_options`) |
| `inc/slots.php` | מועדי הפגישה (שני/שלישי, 20:00–21:00, 21:10–22:10), ולידציה ורשימת מועדים תפוסים (Transient) |
| `inc/assets.php` | טעינת CSS/JS מותנית, `defer`, Preload לגופנים וללוגו |
| `inc/seo.php` | Meta description, Open Graph ו-JSON-LD (NGO + FAQPage) — מושבת אוטומטית כשמותקן תוסף SEO |
| `inc/customizer.php` | ההגדרות שלעיל |
| `inc/accessibility.php` | יצירת עמוד הצהרת הנגישות |
| `assets/css/theme.css` | קובץ המקור של העיצוב (כל הערכים מה-handoff) |
| `assets/js/behaviors.js` | Reveal, Draw-on-scroll, Parallax, קו התקדמות, אקורדיון, יומן, שליחת טופס |
| `bin/build.php` | בונה את הקבצים המוקטנים (`*.min.css` / `*.min.js`) |

לאחר עריכת CSS/JS יש להריץ:

```bash
php bin/build.php
```

## הטופס

- נשלח ל-`admin-post.php` (פעולה `savta_lead`) ועובד גם ללא JavaScript (הפניה ל-`/?savta=sent#signup` או `?savta=error`).
- עם JavaScript: שליחה במקום, הודעות שגיאה לכל שדה (`aria-invalid`, `aria-describedby`), וכרטיס תודה שמחליף את הטופס.
- **Nonce ו-Cache:** ה-Nonce תקף שבוע (כדי לשרוד עמוד שמור ב-LiteSpeed/Cloudflare) וה-JS מבקש Nonce טרי מ-`admin-ajax.php` לפני כל שליחה.
- **מועדים תפוסים:** מועד שנשמר בפנייה מסומן "תפוס" ביומן ונדחה בצד השרת; הרשימה נשמרת ב-Transient ומתרעננת עם כל פנייה (כולל Purge לעמוד הבית ב-LiteSpeed / WP Rocket). מחיקת פנייה לפח משחררת את המועד.
- **Rate limit:** 5 שליחות לשעה לכל IP (`savta_rate_limit` filter).
- שדות שנשלחים: `name, phone, city, age, contact, slot, topic, consent`.

## הערות עיצוב (סטיות מכוונות מה-handoff)

- **ניגודיות (WCAG AA):** טקסטים קטנים בוורוד (מספרי סעיפים, eyebrow, קישור "איך זה עובד?") משתמשים ב-`#8F4531` (6.5:1) במקום `#C98573` (2.8:1); מספרי השלבים הגדולים ב-`#BE7A66` (3.2:1, טקסט גדול). כפתורי ה-pill נשארו בוורוד המקורי `#C98573` לפי החלטת הלקוח (2.8:1); למעבר ל-AA: `--btn-rose: var(--rose-deep)` ב-`:root`.
- `font-weight: 600` אינו קיים ב-Asimon (400/500/700/900) ולכן נכתב 700 — הדפדפן היה בוחר בו ממילא.
- כל ההנפשות (עלי כותרת, ציפורים, אדים, הילה, Reveal, Parallax) מכבדות `prefers-reduced-motion`.
- הלוגו הומר מ-PNG של 951KB ל-WebP (31KB ב-800px, עם גרסאות 400/168), תמונת אפרת ל-WebP, והגופנים מוגשים גם כ-WOFF2.

## בדיקות שבוצעו

- `php -l` על כל הקבצים; PHPCS עם תקן **WordPress** (`phpcs.xml.dist`) — 0 שגיאות.
- axe-core (WCAG 2.x A/AA + best-practice) — 0 הפרות ב-1440px וב-390px.
- השוואה מול `reference.html` ב-1440/1024/768/390: גובה כל סעיף זהה (±3px).
- WordPress 7.1 + PHP 8.4, `WP_DEBUG` — ללא Notice/Warning.
