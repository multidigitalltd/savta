<?php
/**
 * Dashboard schema: every editable text, list, image and setting on the site,
 * with its default value (the design copy, verbatim). The admin pages render
 * from this schema and the sanitizer validates against it, so adding a field
 * is a one-line change.
 *
 * Field types: text | lines (short multi-line, rendered with <br>) | textarea |
 * list (one item per line) | repeater (rows of sub-fields) | image (attachment ID) |
 * toggle | number | weekdays | dates (one YYYY-MM-DD per line).
 *
 * @package Savta
 */

defined( 'ABSPATH' ) || exit;

/**
 * Content schema, grouped by page section.
 *
 * @return array<string, array{label:string, fields:array<string, array>}>
 */
function savta_content_schema(): array {
	static $schema = null;
	if ( null !== $schema ) {
		return $schema;
	}
	$schema = array(
		'nav'    => array(
			'label'  => __( 'ניווט', 'savta' ),
			'fields' => array(
				'nav_links' => array(
					'type'    => 'repeater',
					'label'   => __( 'קישורי התפריט', 'savta' ),
					'fields'  => array(
						'label' => array( 'text', __( 'טקסט', 'savta' ) ),
						'href'  => array( 'text', __( 'עוגן / קישור', 'savta' ) ),
					),
					'default' => array(
						array(
							'label' => __( 'מה זה', 'savta' ),
							'href'  => '#about',
						),
						array(
							'label' => __( 'איך זה עובד', 'savta' ),
							'href'  => '#how-it-works',
						),
						array(
							'label' => __( 'למי זה מתאים', 'savta' ),
							'href'  => '#who',
						),
						array(
							'label' => __( 'שאלות', 'savta' ),
							'href'  => '#faq',
						),
					),
				),
				'nav_cta'   => array(
					'type'    => 'text',
					'label'   => __( 'כפתור בתפריט', 'savta' ),
					'default' => __( 'לדבר עם הסבתא', 'savta' ),
				),
			),
		),
		'hero'   => array(
			'label'  => __( 'פתיח (Hero)', 'savta' ),
			'fields' => array(
				'hero_kicker'  => array(
					'type'    => 'text',
					'label'   => __( 'שורה קטנה מעל הכותרת', 'savta' ),
					'default' => __( 'מיזם חברתי למען הקהילה', 'savta' ),
				),
				'hero_lead_by' => array(
					'type'    => 'text',
					'label'   => __( 'בהובלת', 'savta' ),
					'default' => __( 'בהובלת אפרת ברזל', 'savta' ),
				),
				'hero_title'   => array(
					'type'    => 'lines',
					'label'   => __( 'כותרת ראשית (H1)', 'savta' ),
					'default' => "סבתא\nעל הספסל",
					'help'    => __( 'כל שורה כאן היא שורה בכותרת.', 'savta' ),
				),
				'hero_lede'    => array(
					'type'    => 'textarea',
					'label'   => __( 'משפט פתיחה', 'savta' ),
					'default' => __( 'לפעמים כל מה שצריך הוא מישהי טובה שתשב איתך רגע — ותקשיב באמת.', 'savta' ),
				),
				'hero_btn'     => array(
					'type'    => 'text',
					'label'   => __( 'כפתור ראשי', 'savta' ),
					'default' => __( 'אני רוצה לדבר עם הסבתא', 'savta' ),
				),
				'hero_link'    => array(
					'type'    => 'text',
					'label'   => __( 'קישור משני', 'savta' ),
					'default' => __( 'איך זה עובד?', 'savta' ),
				),
				'hero_info'    => array(
					'type'    => 'textarea',
					'label'   => __( 'פסקת מידע מתחת לפתיח', 'savta' ),
					'default' => __( 'שיחה אישית, חינמית ודיסקרטית עם אישה עתירת ניסיון חיים, מכילה ומקשיבה — בעלת לב רחב ונוכחות מרגיעה. מקום לפרוק, לנשום, לעשות קצת סדר, ולצאת עם כוח נוסף לצעד הבא.', 'savta' ),
				),
				'hero_facts'   => array(
					'type'    => 'repeater',
					'label'   => __( 'שלוש העובדות', 'savta' ),
					'fields'  => array(
						'label' => array( 'text', __( 'תווית', 'savta' ) ),
						'value' => array( 'lines', __( 'ערך', 'savta' ) ),
					),
					'default' => array(
						array(
							'label' => __( 'מסגרת', 'savta' ),
							'value' => "פרויקט למען\nהקהילה",
						),
						array(
							'label' => __( 'עלות', 'savta' ),
							'value' => __( 'ללא עלות', 'savta' ),
						),
						array(
							'label' => __( 'זימון', 'savta' ),
							'value' => "בתיאום מראש\nבלבד",
						),
					),
				),
			),
		),
		'intro'  => array(
			'label'  => __( 'פתיח שני (ספסל בגן)', 'savta' ),
			'fields' => array(
				'intro_big'  => array(
					'type'    => 'textarea',
					'label'   => __( 'טקסט גדול', 'savta' ),
					'default' => __( 'יש רגעים שבהם הכול נראה "בסדר" מבחוץ, אבל בפנים יש עומס, מחשבות, דאגה או תחושה שאין באמת עם מי לדבר.', 'savta' ),
				),
				'intro_body' => array(
					'type'    => 'textarea',
					'label'   => __( 'טקסט משני', 'savta' ),
					'default' => __( 'לא תמיד צריך פתרונות גדולים. לפעמים צריך מקום שקט, לב פתוח, ואוזן קשבת של מישהי שכבר ראתה הרבה בחיים — ויודעת להקשיב בלי לשפוט.', 'savta' ),
				),
			),
		),
		'about'  => array(
			'label'  => __( '01 מה זה', 'savta' ),
			'fields' => array(
				'about_title' => array(
					'type'    => 'lines',
					'label'   => __( 'כותרת', 'savta' ),
					'default' => "מה זה\n\"סבתא על הספסל\"?",
				),
				'about_p1'    => array(
					'type'    => 'textarea',
					'label'   => __( 'פסקה 1', 'savta' ),
					'default' => __( '"סבתא על הספסל" הוא מיזם חברתי שנולד מתוך צורך אמיתי בקהילה: לאפשר לאנשים לעצור לרגע, לשבת לשיחה אישית, לפרוק מה שיושב על הלב, ולקבל הקשבה טובה, מכבדת ולא שיפוטית.', 'savta' ),
				),
				'about_pull'  => array(
					'type'    => 'textarea',
					'label'   => __( 'ציטוט מודגש', 'savta' ),
					'default' => __( 'זו לא קליניקה. זה לא טיפול. וזו לא שיחה שמבטיחה לפתור הכול.', 'savta' ),
				),
				'about_p2'    => array(
					'type'    => 'textarea',
					'label'   => __( 'פסקה 2', 'savta' ),
					'default' => __( 'זו שיחה אנושית, חמה ודיסקרטית — עם "סבתא" מקשיבה, מנוסה ורגישה, שעברה הכוונה מתאימה ומגיעה עם ניסיון חיים, לב פתוח ויכולת לראות את האדם שמולה.', 'savta' ),
				),
				'about_goal'  => array(
					'type'    => 'textarea',
					'label'   => __( 'משפט המטרה (ליד הכוס)', 'savta' ),
					'default' => __( 'המטרה פשוטה: לתת מקום לדבר, לעשות סדר, ולהרגיש לרגע שלא סוחבים לבד.', 'savta' ),
				),
			),
		),
		'how'    => array(
			'label'  => __( '02 איך זה עובד', 'savta' ),
			'fields' => array(
				'how_title' => array(
					'type'    => 'text',
					'label'   => __( 'כותרת', 'savta' ),
					'default' => __( 'איך זה עובד?', 'savta' ),
				),
				'how_steps' => array(
					'type'    => 'repeater',
					'label'   => __( 'השלבים', 'savta' ),
					'fields'  => array(
						'title' => array( 'text', __( 'כותרת', 'savta' ) ),
						'text'  => array( 'textarea', __( 'טקסט', 'savta' ) ),
					),
					'default' => array(
						array(
							'title' => __( 'משאירים פרטים', 'savta' ),
							'text'  => __( 'ממלאים טופס קצר ודיסקרטי, כדי שנוכל להבין אם השיחה מתאימה ולחזור אלייך לתיאום.', 'savta' ),
						),
						array(
							'title' => __( 'מתאמים זמן', 'savta' ),
							'text'  => __( 'השיחות מתקיימות בתיאום מראש בלבד, כדי לשמור על פרטיות, זמינות ואווירה נעימה.', 'savta' ),
						),
						array(
							'title' => __( 'יושבים לשיחה', 'savta' ),
							'text'  => __( 'שיחה אישית של 40 דקות עד שעה עם "סבתא" מקשיבה — מקום לפרוק, לשתף, לנשום ולעשות סדר.', 'savta' ),
						),
						array(
							'title' => __( 'יוצאים עם צעד קטן', 'savta' ),
							'text'  => __( 'בסיום השיחה מנסים לזהות יחד נקודה אחת קטנה וברורה שיכולה לתת כוח להמשך.', 'savta' ),
						),
					),
				),
			),
		),
		'who'    => array(
			'label'  => __( '03 למי', 'savta' ),
			'fields' => array(
				'who_title' => array(
					'type'    => 'lines',
					'label'   => __( 'כותרת', 'savta' ),
					'default' => "למי השיחה\nיכולה להתאים?",
				),
				'who_lede'  => array(
					'type'    => 'textarea',
					'label'   => __( 'פסקה', 'savta' ),
					'default' => __( 'למי שמרגישה צורך לעצור רגע, לשתף, לפרוק או לעשות סדר — בלי לחץ, בלי ביקורת ובלי שיפוטיות.', 'savta' ),
				),
				'who_pill'  => array(
					'type'    => 'text',
					'label'   => __( 'תגית', 'savta' ),
					'default' => __( 'מגיל 20 ומעלה', 'savta' ),
				),
				'who_items' => array(
					'type'    => 'list',
					'label'   => __( 'רשימת הסימונים (שורה לכל פריט)', 'savta' ),
					'default' => array(
						__( 'מרגישה שהיא סוחבת הרבה לבד', 'savta' ),
						__( 'צריכה אוזן קשבת אמיתית', 'savta' ),
						__( 'נמצאת בתקופה עמוסה או מבלבלת', 'savta' ),
						__( 'רוצה לדבר עם מישהי מנוסה, רגועה וטובת לב', 'savta' ),
						__( 'צריכה רגע של חיזוק, סדר ונשימה', 'savta' ),
						__( 'לא מחפשת טיפול, אלא שיחה אנושית פשוטה וטובה', 'savta' ),
					),
				),
				'who_note'  => array(
					'type'    => 'textarea',
					'label'   => __( 'הערה קטנה', 'savta' ),
					'default' => __( 'המיזם פועל כעת בבני ברק, ובעזרת ה׳ יורחב בהמשך לערים נוספות. מספר המקומות מוגבל והשיחות יתקיימו בהדרגה ובאחריות.', 'savta' ),
				),
			),
		),
		'efrat'  => array(
			'label'  => __( '04 אפרת ברזל', 'savta' ),
			'fields' => array(
				'efrat_title' => array(
					'type'    => 'text',
					'label'   => __( 'כותרת', 'savta' ),
					'default' => __( 'המיזם בהובלת אפרת ברזל', 'savta' ),
				),
				'efrat_p1'    => array(
					'type'    => 'textarea',
					'label'   => __( 'פסקה 1', 'savta' ),
					'default' => __( 'המיזם "סבתא על הספסל" פועל בהובלתה של הגב\' אפרת ברזל — סופרת, מטפלת רגשית ואשת תקשורת ידועה.', 'savta' ),
				),
				'efrat_p2'    => array(
					'type'    => 'textarea',
					'label'   => __( 'פסקה 2', 'savta' ),
					'default' => __( 'אפרת מובילה את המיזם מתוך רצון לתת מענה קהילתי רך, אחראי ואנושי לצורך הגדול שנוצר בשטח: צורך באוזן קשבת, בחיבור אנושי, במקום שבו אפשר לדבר בפשטות, בכבוד ובלב פתוח.', 'savta' ),
				),
				'efrat_quote' => array(
					'type'    => 'textarea',
					'label'   => __( 'ציטוט מודגש', 'savta' ),
					'default' => __( 'המטרה היא לבנות מרחב קהילתי שמחזיר למרכז את הדבר הכי בסיסי והכי חסר לפעמים: מישהי טובה שמקשיבה באמת.', 'savta' ),
				),
				'efrat_alt'   => array(
					'type'    => 'text',
					'label'   => __( 'טקסט חלופי לתמונה', 'savta' ),
					'default' => __( 'אפרת ברזל', 'savta' ),
				),
			),
		),
		'first'  => array(
			'label'  => __( '05 הסבתא הראשונה', 'savta' ),
			'fields' => array(
				'first_title'   => array(
					'type'    => 'text',
					'label'   => __( 'כותרת', 'savta' ),
					'default' => __( '"הסבתא" הראשונה שלנו', 'savta' ),
				),
				'first_p1'      => array(
					'type'    => 'textarea',
					'label'   => __( 'פסקה 1', 'savta' ),
					'default' => __( '"הסבתא" הראשונה במיזם היא אשת חינוך ותיקה, עם למעלה מ־40 שנות ניסיון בחינוך, מתוכן 13 שנים כמנהלת בבית ספר ידוע.', 'savta' ),
				),
				'first_p2'      => array(
					'type'    => 'textarea',
					'label'   => __( 'פסקה 2', 'savta' ),
					'default' => __( 'לאורך השנים היא ליוותה תלמידות, הורים וצוותים חינוכיים, מתוך חכמה, רגישות, אחריות והבנה עמוקה לאנשים.', 'savta' ),
				),
				'first_p3'      => array(
					'type'    => 'textarea',
					'label'   => __( 'פסקה 3', 'savta' ),
					'default' => __( 'היום היא מביאה אל המיזם את ניסיון החיים, הלב הרחב והיכולת המיוחדת שלה להקשיב — כדי להיות שם עבור מי שצריכה שיחה טובה, רגועה ומחזקת.', 'savta' ),
				),
				'first_quote'   => array(
					'type'    => 'textarea',
					'label'   => __( 'ציטוט מודגש', 'savta' ),
					'default' => __( 'לא כמטפלת. לא כשופטת. אלא כסבתא מקשיבה — עם לב פתוח ונוכחות טובה.', 'savta' ),
				),
				'first_caption' => array(
					'type'    => 'text',
					'label'   => __( 'כיתוב במקום השמור (כשאין תמונה)', 'savta' ),
					'default' => __( 'איור: סבתא יושבת על ספסל', 'savta' ),
				),
			),
		),
		'notice' => array(
			'label'  => __( 'חשוב לדעת', 'savta' ),
			'fields' => array(
				'notice_title' => array(
					'type'    => 'text',
					'label'   => __( 'כותרת', 'savta' ),
					'default' => __( 'חשוב לדעת', 'savta' ),
				),
				'notice_p1'    => array(
					'type'    => 'textarea',
					'label'   => __( 'פסקה 1', 'savta' ),
					'default' => __( 'השיחה במסגרת "סבתא על הספסל" היא שיחת הקשבה קהילתית. היא אינה טיפול רגשי, אינה אבחון, ואינה מחליפה ייעוץ מקצועי או מענה חירום.', 'savta' ),
				),
				'notice_p2'    => array(
					'type'    => 'textarea',
					'label'   => __( 'פסקה 2', 'savta' ),
					'default' => __( 'המטרה היא לתת מקום בטוח לשיחה, הקשבה, סדר וחיזוק ראשוני.', 'savta' ),
				),
				'notice_p3'    => array(
					'type'    => 'textarea',
					'label'   => __( 'פסקה 3', 'savta' ),
					'default' => __( 'במקרים שבהם נראה שיש צורך בעזרה מקצועית נוספת, נעודד פנייה לגורם מתאים.', 'savta' ),
				),
			),
		),
		'quotes' => array(
			'label'  => __( '06 משפטים', 'savta' ),
			'fields' => array(
				'quotes_title' => array(
					'type'    => 'lines',
					'label'   => __( 'כותרת', 'savta' ),
					'default' => "משפטים שסבתא\nאומרת על הספסל",
				),
				'quotes_lede'  => array(
					'type'    => 'textarea',
					'label'   => __( 'משפט לוואי', 'savta' ),
					'default' => __( 'קחי אחד לדרך. אפשר גם להדפיס אותם ולתלות על המקרר.', 'savta' ),
				),
				'quotes_items' => array(
					'type'    => 'list',
					'label'   => __( 'המשפטים (שורה לכל כרטיס)', 'savta' ),
					'default' => array(
						__( 'גם יום קטן נחשב.', 'savta' ),
						__( 'את לא צריכה להחזיק הכל לבד.', 'savta' ),
						__( 'מותר לנוח באמצע.', 'savta' ),
						__( 'מה שעברת בנה אותך.', 'savta' ),
						__( 'לשאול זו לא חולשה.', 'savta' ),
						__( 'יש מי שמקשיב לך.', 'savta' ),
						__( 'צעד אחד קטן זה כבר כיוון.', 'savta' ),
						__( 'לא חייבים תשובה מיד.', 'savta' ),
						__( 'גם השקט הוא תשובה.', 'savta' ),
						__( 'את שווה את הזמן שלך.', 'savta' ),
						__( 'מחר יהיה יום אחר.', 'savta' ),
						__( 'להתחיל מחדש זה גם אומץ.', 'savta' ),
						__( 'לא כל דבר צריך פתרון. לפעמים מספיק שיקשיבו.', 'savta' ),
						__( 'הלב יודע קצב אחר מהלוח שנה.', 'savta' ),
						__( 'מה שמרגיש בלתי אפשרי היום, ייראה אחרת בעוד שבוע.', 'savta' ),
						__( 'אין בושה בלהיות עייפה.', 'savta' ),
						__( 'גם דלת סגורה מלמדת משהו.', 'savta' ),
						__( 'את בדרך, גם כשזה לא מרגיש כך.', 'savta' ),
						__( 'את לא לבד בזה.', 'savta' ),
						__( 'גם לבקש עזרה זה כוח.', 'savta' ),
					),
				),
			),
		),
		'faq'    => array(
			'label'  => __( '07 שאלות', 'savta' ),
			'fields' => array(
				'faq_title' => array(
					'type'    => 'lines',
					'label'   => __( 'כותרת', 'savta' ),
					'default' => "שאלות\nשרצית לשאול",
				),
				'faq_lede'  => array(
					'type'    => 'textarea',
					'label'   => __( 'משפט לוואי', 'savta' ),
					'default' => __( 'ואם נשארה שאלה שלא ענינו עליה — אפשר להשאיר פרטים ונחזור אלייך.', 'savta' ),
				),
				'faq_items' => array(
					'type'    => 'repeater',
					'label'   => __( 'שאלות ותשובות', 'savta' ),
					'fields'  => array(
						'q' => array( 'text', __( 'שאלה', 'savta' ) ),
						'a' => array( 'textarea', __( 'תשובה', 'savta' ) ),
					),
					'default' => array(
						array(
							'q' => __( 'האם השיחה עולה כסף?', 'savta' ),
							'a' => __( 'לא. השיחה ניתנת תמיד ללא עלות — מדובר בעמותה ללא מטרות רווח, הפועלת למען הקהילה.', 'savta' ),
						),
						array(
							'q' => __( 'האם אפשר להגיע בלי תיאום מראש?', 'savta' ),
							'a' => __( 'לא. השיחות מתקיימות תמיד בתיאום מראש בלבד, כדי לשמור על פרטיות, זמינות והתאמה נכונה.', 'savta' ),
						),
						array(
							'q' => __( 'כמה זמן נמשכת שיחה?', 'savta' ),
							'a' => __( 'כל שיחה נמשכת בין 40 דקות לשעה.', 'savta' ),
						),
						array(
							'q' => __( 'היכן מתקיימת השיחה?', 'savta' ),
							'a' => __( 'השיחות מתקיימות במקומות קהילתיים שנבחרו בקפידה: חדר פרטי ושקט או משרד נעים, בסביבה אסתטית ומזמינה, השומרת על פרטיות מלאה ועל תחושת נינוחות. המיזם פועל כעת בבני ברק, ובעזרת ה׳ יורחב בהמשך לערים נוספות.', 'savta' ),
						),
						array(
							'q' => __( 'האם זו שיחה טיפולית?', 'savta' ),
							'a' => __( 'לא. זו שיחת הקשבה ותמיכה קהילתית. היא אינה מחליפה טיפול מקצועי, אבל יכולה לתת מקום לפרוק, לעשות סדר ולקבל חיזוק.', 'savta' ),
						),
						array(
							'q' => __( 'האם השיחה דיסקרטית?', 'savta' ),
							'a' => __( 'כן. השיחה מתקיימת באווירה מכבדת ודיסקרטית, תוך שמירה על פרטיות המשתתפות, למעט מצבים חריגים שבהם נדרש לפנות לעזרה מתאימה.', 'savta' ),
						),
						array(
							'q' => __( 'למי מתאים להירשם?', 'savta' ),
							'a' => __( 'למי שמרגישה צורך בשיחה אישית, באוזן קשבת, בסדר פנימי ובחיזוק אנושי פשוט.', 'savta' ),
						),
					),
				),
			),
		),
		'signup' => array(
			'label'  => __( '08 טופס תיאום', 'savta' ),
			'fields' => array(
				'signup_title'   => array(
					'type'    => 'lines',
					'label'   => __( 'כותרת', 'savta' ),
					'default' => "רוצה לתאם\nפגישה עם הסבתא?",
				),
				'signup_lede'    => array(
					'type'    => 'textarea',
					'label'   => __( 'פסקה', 'savta' ),
					'default' => __( 'אנחנו פותחים את השיחות הראשונות בהדרגה ובאחריות. אפשר להשאיר פרטים, ונחזור אלייך לתיאום שיחה או להסבר נוסף.', 'savta' ),
				),
				'signup_limited' => array(
					'type'    => 'text',
					'label'   => __( 'שורת הדגשה (אפשר להשאיר ריק)', 'savta' ),
					'default' => __( 'מספר המקומות בשלב הראשון מוגבל.', 'savta' ),
				),
				'cal_note'       => array(
					'type'    => 'textarea',
					'label'   => __( 'הסבר ביומן', 'savta' ),
					'default' => __( 'השיחות מתקיימות בימי שני ושלישי בערב, בין 20:00 ל־22:30. בחרי מועד ונאשר אותו בשיחה.', 'savta' ),
				),
				'cal_note_small' => array(
					'type'    => 'text',
					'label'   => __( 'הערה קטנה ביומן', 'savta' ),
					'default' => __( 'לא מצאת מועד מתאים? אפשר לכתוב לנו בשדה למטה ונמצא זמן אחר.', 'savta' ),
				),
				'form_consent'   => array(
					'type'    => 'textarea',
					'label'   => __( 'טקסט תיבת האישור', 'savta' ),
					'default' => __( 'ידוע לי שמדובר בשיחת הקשבה קהילתית שאינה מחליפה טיפול מקצועי.', 'savta' ),
				),
				'form_submit'    => array(
					'type'    => 'text',
					'label'   => __( 'כפתור שליחה', 'savta' ),
					'default' => __( 'אני רוצה שיחזרו אליי', 'savta' ),
				),
				'thanks_title'   => array(
					'type'    => 'text',
					'label'   => __( 'כותרת תודה', 'savta' ),
					'default' => __( 'תודה, הפרטים התקבלו.', 'savta' ),
				),
				'thanks_text'    => array(
					'type'    => 'text',
					'label'   => __( 'טקסט תודה', 'savta' ),
					'default' => __( 'נחזור אלייך בהקדם לתיאום או להסבר נוסף.', 'savta' ),
				),
			),
		),
		'footer' => array(
			'label'  => __( 'פוטר', 'savta' ),
			'fields' => array(
				'footer_title' => array(
					'type'    => 'text',
					'label'   => __( 'כותרת', 'savta' ),
					'default' => __( 'סבתא על הספסל', 'savta' ),
				),
				'footer_text'  => array(
					'type'    => 'text',
					'label'   => __( 'משפט', 'savta' ),
					'default' => __( 'מיזם חברתי של הקשבה, חום אנושי ותמיכה קהילתית.', 'savta' ),
				),
				'footer_lead'  => array(
					'type'    => 'text',
					'label'   => __( 'בהובלת', 'savta' ),
					'default' => __( 'בהובלת אפרת ברזל.', 'savta' ),
				),
				'footer_small' => array(
					'type'    => 'text',
					'label'   => __( 'הסתייגות', 'savta' ),
					'default' => __( 'השירות אינו מחליף טיפול מקצועי או מענה חירום.', 'savta' ),
				),
			),
		),
	);
	return $schema;
}

/**
 * Settings schema (functional switches), grouped.
 *
 * @return array<string, array{label:string, fields:array<string, array>}>
 */
function savta_settings_schema(): array {
	static $schema = null;
	if ( null !== $schema ) {
		return $schema;
	}
	$schema = array(
		'general'  => array(
			'label'  => __( 'כללי', 'savta' ),
			'fields' => array(
				'lead_emails'     => array(
					'type'    => 'text',
					'label'   => __( 'כתובות לקבלת פניות', 'savta' ),
					'default' => '',
					'help'    => __( 'מופרדות בפסיק. ריק = כתובת מנהל האתר.', 'savta' ),
				),
				'seo_description' => array(
					'type'    => 'textarea',
					'label'   => __( 'תיאור לגוגל (meta description)', 'savta' ),
					'default' => '',
					'help'    => __( 'ריק = תיאור האתר מההגדרות הכלליות. מושבת אוטומטית כשמותקן תוסף SEO.', 'savta' ),
				),
				'ga4_id'          => array(
					'type'    => 'text',
					'label'   => __( 'Google Analytics 4 (Measurement ID)', 'savta' ),
					'default' => '',
					'help'    => __( 'בפורמט G-XXXXXXXXXX. ריק = ללא מעקב.', 'savta' ),
				),
			),
		),
		'sections' => array(
			'label'  => __( 'הצגת סעיפים', 'savta' ),
			'fields' => array(
				'show_intro'  => array(
					'type'    => 'toggle',
					'label'   => __( 'פתיח שני (ספסל בגן)', 'savta' ),
					'default' => true,
				),
				'show_about'  => array(
					'type'    => 'toggle',
					'label'   => __( '01 מה זה', 'savta' ),
					'default' => true,
				),
				'show_how'    => array(
					'type'    => 'toggle',
					'label'   => __( '02 איך זה עובד', 'savta' ),
					'default' => true,
				),
				'show_who'    => array(
					'type'    => 'toggle',
					'label'   => __( '03 למי', 'savta' ),
					'default' => true,
				),
				'show_efrat'  => array(
					'type'    => 'toggle',
					'label'   => __( '04 אפרת ברזל', 'savta' ),
					'default' => true,
				),
				'show_first'  => array(
					'type'    => 'toggle',
					'label'   => __( '05 הסבתא הראשונה', 'savta' ),
					'default' => true,
				),
				'show_notice' => array(
					'type'    => 'toggle',
					'label'   => __( 'חשוב לדעת', 'savta' ),
					'default' => true,
				),
				'show_quotes' => array(
					'type'    => 'toggle',
					'label'   => __( '06 משפטים', 'savta' ),
					'default' => true,
				),
				'show_faq'    => array(
					'type'    => 'toggle',
					'label'   => __( '07 שאלות', 'savta' ),
					'default' => true,
				),
				'show_signup' => array(
					'type'    => 'toggle',
					'label'   => __( '08 טופס תיאום', 'savta' ),
					'default' => true,
				),
			),
		),
		'motion'   => array(
			'label'  => __( 'אנימציות', 'savta' ),
			'fields' => array(
				'animations' => array(
					'type'    => 'toggle',
					'label'   => __( 'אנימציות כניסה ו-Parallax', 'savta' ),
					'default' => true,
				),
				'petals'     => array(
					'type'    => 'toggle',
					'label'   => __( 'לבבות, עלי כותרת וציפורים בפתיח', 'savta' ),
					'default' => true,
				),
				'steam'      => array(
					'type'    => 'toggle',
					'label'   => __( 'אדים מהכוס', 'savta' ),
					'default' => true,
				),
			),
		),
		'calendar' => array(
			'label'  => __( 'יומן מועדים', 'savta' ),
			'fields' => array(
				'calendar_enabled' => array(
					'type'    => 'toggle',
					'label'   => __( 'להציג בחירת מועד בטופס', 'savta' ),
					'default' => true,
				),
				'slot_days'        => array(
					'type'    => 'weekdays',
					'label'   => __( 'ימי הפגישות', 'savta' ),
					'default' => array( 1, 2 ),
				),
				'slot_times'       => array(
					'type'    => 'list',
					'label'   => __( 'שעות (שורה לכל חלון)', 'savta' ),
					'default' => array( '20:00–21:00', '21:10–22:10' ),
					'help'    => __( 'בפורמט HH:MM–HH:MM.', 'savta' ),
				),
				'slot_day_count'   => array(
					'type'    => 'number',
					'label'   => __( 'כמה ימים קרובים להציג', 'savta' ),
					'default' => 4,
					'min'     => 1,
					'max'     => 12,
				),
				'slot_horizon'     => array(
					'type'    => 'number',
					'label'   => __( 'עד כמה ימים קדימה אפשר לבחור', 'savta' ),
					'default' => 60,
					'min'     => 7,
					'max'     => 365,
				),
				'slot_blocked'     => array(
					'type'    => 'dates',
					'label'   => __( 'תאריכים חסומים (שורה לכל תאריך)', 'savta' ),
					'default' => array(),
					'help'    => __( 'בפורמט YYYY-MM-DD, למשל חגים.', 'savta' ),
				),
			),
		),
		'security' => array(
			'label'  => __( 'הגנה על הטופס', 'savta' ),
			'fields' => array(
				'rate_limit'  => array(
					'type'    => 'number',
					'label'   => __( 'מקסימום שליחות לכתובת IP', 'savta' ),
					'default' => 5,
					'min'     => 1,
					'max'     => 100,
				),
				'rate_window' => array(
					'type'    => 'number',
					'label'   => __( 'בחלון של (דקות)', 'savta' ),
					'default' => 60,
					'min'     => 1,
					'max'     => 1440,
				),
			),
		),
	);
	return $schema;
}

/**
 * Image slots (attachment IDs), edited on the Images tab.
 *
 * @return array<string, array{label:string, help:string}>
 */
function savta_image_schema(): array {
	return array(
		'img_logo'   => array(
			'label' => __( 'לוגו (פתיח ותפריט)', 'savta' ),
			'help'  => __( 'ריבוע, מומלץ 800×800 עם רקע בגוון הקרם.', 'savta' ),
		),
		'img_intro'  => array(
			'label' => __( 'פתיח שני: ספסל בגן', 'savta' ),
			'help'  => __( 'יחס 4:5.', 'savta' ),
		),
		'img_about'  => array(
			'label' => __( '01: שתי נשים על ספסל', 'savta' ),
			'help'  => __( 'יחס 3:2.', 'savta' ),
		),
		'img_who'    => array(
			'label' => __( '03: ידיים עם כוס תה', 'savta' ),
			'help'  => __( 'ריבוע.', 'savta' ),
		),
		'img_efrat'  => array(
			'label' => __( '04: אפרת ברזל', 'savta' ),
			'help'  => __( 'לאורך, יחס ~4:5.', 'savta' ),
		),
		'img_first'  => array(
			'label' => __( '05: הסבתא הראשונה', 'savta' ),
			'help'  => __( 'ריבוע. עד שתועלה תמונה מוצג מקום שמור.', 'savta' ),
		),
		'img_signup' => array(
			'label' => __( '08: ספסל ריק', 'savta' ),
			'help'  => __( 'יחס 3:2.', 'savta' ),
		),
		'og_image'   => array(
			'label' => __( 'תמונת שיתוף (Open Graph)', 'savta' ),
			'help'  => __( 'מומלץ 1200×630. ריק = הלוגו.', 'savta' ),
		),
	);
}
