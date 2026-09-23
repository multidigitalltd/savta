<?php
/**
 * Site copy and data. Everything is filterable so a client-editing layer
 * (ACF, Customizer, options) can override any list without touching templates.
 *
 * Copy is used verbatim from the design handoff, including quotation marks and em-dashes.
 *
 * @package Savta
 */

defined( 'ABSPATH' ) || exit;

/**
 * Navigation anchors (fallback when no menu is assigned to `primary`).
 *
 * @return array<int, array{href:string,label:string}>
 */
function savta_nav_links(): array {
	return apply_filters(
		'savta_nav_links',
		array(
			array(
				'href'  => '#about',
				'label' => __( 'מה זה', 'savta' ),
			),
			array(
				'href'  => '#how-it-works',
				'label' => __( 'איך זה עובד', 'savta' ),
			),
			array(
				'href'  => '#who',
				'label' => __( 'למי זה מתאים', 'savta' ),
			),
			array(
				'href'  => '#faq',
				'label' => __( 'שאלות', 'savta' ),
			),
		)
	);
}

/**
 * "How it works" steps.
 *
 * @return array<int, array{title:string,text:string}>
 */
function savta_steps(): array {
	return apply_filters(
		'savta_steps',
		array(
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
		)
	);
}

/**
 * "Who is it for" checklist.
 *
 * @return string[]
 */
function savta_audience(): array {
	return apply_filters(
		'savta_audience',
		array(
			__( 'מרגישה שהיא סוחבת הרבה לבד', 'savta' ),
			__( 'צריכה אוזן קשבת אמיתית', 'savta' ),
			__( 'נמצאת בתקופה עמוסה או מבלבלת', 'savta' ),
			__( 'רוצה לדבר עם מישהי מנוסה, רגועה וטובת לב', 'savta' ),
			__( 'צריכה רגע של חיזוק, סדר ונשימה', 'savta' ),
			__( 'לא מחפשת טיפול, אלא שיחה אנושית פשוטה וטובה', 'savta' ),
		)
	);
}

/**
 * Quote cards ("things savta says on the bench").
 *
 * @return string[]
 */
function savta_quotes(): array {
	return apply_filters(
		'savta_quotes',
		array(
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
		)
	);
}

/**
 * FAQ entries.
 *
 * @return array<int, array{q:string,a:string}>
 */
function savta_faqs(): array {
	return apply_filters(
		'savta_faqs',
		array(
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
		)
	);
}

/**
 * Theme image registry: file, intrinsic size and alt text.
 * Sizes are real pixel dimensions so `<img width height>` reserves space (zero CLS).
 *
 * @return array<string, array{file:string,w:int,h:int,alt:string}>
 */
function savta_images(): array {
	return apply_filters(
		'savta_images',
		array(
			'logo'       => array(
				'file' => 'logo-800.webp',
				'w'    => 800,
				'h'    => 800,
				'alt'  => __( 'סבתא על הספסל: איור של סבתא יושבת על ספסל בגן', 'savta' ),
			),
			'logo-small' => array(
				'file' => 'logo-168.webp',
				'w'    => 168,
				'h'    => 168,
				'alt'  => __( 'סבתא על הספסל', 'savta' ),
			),
			'intro'      => array(
				'file' => 'bench-garden.webp',
				'w'    => 890,
				'h'    => 1112,
				'alt'  => __( 'איור: ספסל בגן, אור אחר צהריים', 'savta' ),
			),
			'about'      => array(
				'file' => 'two-women-bench.webp',
				'w'    => 728,
				'h'    => 485,
				'alt'  => __( 'איור: שתי נשים יושבות בשיחה על ספסל', 'savta' ),
			),
			'who'        => array(
				'file' => 'tea-hands.webp',
				'w'    => 676,
				'h'    => 676,
				'alt'  => __( 'איור: שתי ידיים עם כוס תה', 'savta' ),
			),
			'efrat'      => array(
				'file' => 'efrat.webp',
				'w'    => 480,
				'h'    => 604,
				'alt'  => __( 'אפרת ברזל', 'savta' ),
			),
			'signup'     => array(
				'file' => 'empty-bench.webp',
				'w'    => 756,
				'h'    => 504,
				'alt'  => __( 'איור: ספסל ריק ממתין בגן', 'savta' ),
			),
		)
	);
}

/**
 * Absolute URL of a theme image file.
 *
 * @param string $file File name inside assets/images.
 * @return string
 */
function savta_image_url( string $file ): string {
	return SAVTA_URI . '/assets/images/' . $file;
}
