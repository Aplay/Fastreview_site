(function($) {
if (elFinder && elFinder.prototype.options && elFinder.prototype.options.i18n) 
	elFinder.prototype.options.i18n.ua = {
		/* errors */
		'Root directory does not exists'       : 'Коренева директорія не існує',
		'Unable to connect to backend'         : 'Не вдалося з&rsquo;єднатися з сервером',
		'Access denied'                        : 'Доступ заборонено',
		'Invalid backend configuration'        : 'Помилки у відповідь сервера',
		'Unknown command'                      : 'Невідома команда',
		'Command not allowed'                  : 'Виконання команди заборонено',
		'Invalid parameters'                   : 'Хибний параметри',
		'File not found'                       : 'Файл не знайдено',
		'Invalid name'                         : 'Некоректний ім&rsquo;я',
		'File or folder with the same name already exists' : 'Файл або папка з такою назвою вже існує',
		'Unable to rename file'                : 'Не вдалося перейменувати файл',
		'Unable to create folder'              : 'Не вдалося створити папку',
		'Unable to create file'                : 'Не вдалося створити файл',  
		'No file to upload'                    : 'Немає файлів для завантаження',
		'Select at least one file to upload'   : 'Виберіть, як мінімум, один файл для завантаження',
		'File exceeds the maximum allowed filesize' : 'Размер файла превышает максимально разрешенный размер',
		'Not allowed file type'                 : 'Неразрешенный тип файла',
		'Unable to upload file'                 : 'Не вдалося завантажити файл',
		'Unable to upload files'                : 'Не вдалося отримати файли',
		'Unable to remove file'                 : 'Не вдалося видалити файл',
		'Unable to save uploaded file'          : 'Не вдалося зберегти завантажений файл',
		'Some files was not uploaded'           : 'Деякі файли не вдалося завантажити',
		'Unable to copy into itself'            : 'Неможливо скопіювати в себе',
		'Unable to move files'                  : 'Не вдалося перемістити файли',
		'Unable to copy files'                  : 'Не вдалося скопіювати файли',
		'Unable to create file copy'            : 'Не вдалося створити копію файлу',
		'File is not an image'                  : 'Файл не є зображенням',
		'Unable to resize image'                : 'Не вдалося змінити розміри зображення',
		'Unable to write to file'               : 'Не вдалося записати файл',
		'Unable to create archive'              : 'Не вдалося створити архів',
		'Unable to extract files from archive'  : 'Не вдалося витягти файли з архіву',
		'Unable to open broken link'            : 'Неможливо відкрити биту посилання',
		'File URL disabled by connector config' : 'Доступ до адрес файлів заборонений налаштуваннями коннектора',
		/* statusbar */
		'items'          : 'об&rsquo;єктів',
		'selected items' : 'вибрано об&rsquo;єктів',
		/* commands/buttons */
		'Back'                    : 'Назад',
		'Reload'                  : 'Оновити',
		'Open'                    : 'Відкрити',
		'Preview with Quick Look' : 'Швидкий перегляд',
		'Select file'             : 'Вибрати файл',
		'New folder'              : 'Нова папка',
		'New text file'           : 'Новий файл',
		'Upload files'            : 'Завантажити файли',
		'Copy'                    : 'Копіювати',
		'Cut'                     : 'Вирізати',
		'Paste'                   : 'Вставити',
		'Duplicate'               : 'Дублювати',
		'Remove'                  : 'Видалити',
		'Rename'                  : 'Перейменувати',
		'Edit text file'          : 'Редагувати файл',
		'View as icons'           : 'Іконки',
		'View as list'            : 'Список',
		'Resize image'            : 'Розмір зображення',
		'Create archive'          : 'Новий архів',
		'Uncompress archive'      : 'Розпакувати архів',
		'Get info'                : 'Властивості',
		'Help'                    : 'Допомога',
		'Dock/undock filemanger window' : 'Від&rsquo;єднати/приєднати Менеджер файлів до сторінки',
		/* upload/get info dialogs */
		'Maximum allowed files size' : 'Максимальний розмір файлів',
		'Add field'   : 'Додати поле',
		'File info'   : 'Властивості файлу',
		'Folder info' : 'Параметри папки',
		'Name'        : 'Назва',
		'Kind'        : 'Тип',
		'Size'        : 'Розмір',
		'Modified'    : 'Змінено',
		'Permissions' : 'Доступ',
		'Link to'     : 'Вказує',
		'Dimensions'  : 'Дозвіл',
		'Confirmation required' : 'Необхідно підтвердити',
		'Are you shure you want to remove files?<br /> This cannot be undone!' : 'Ви впевнені, що хочете видалити файл? <br /> Дія є незворотнім.',
		/* permissions */
		'read'        : 'читання',
		'write'       : 'запис',
		'remove'      : 'видалення',
		/* dates */
		'Jan'         : 'Січень',
		'Feb'         : 'Лютий',
		'Mar'         : 'Березня',
		'Apr'         : 'Квітня',
		'May'         : 'Травень',
		'Jun'         : 'Червня',
		'Jul'         : 'Липень',
		'Aug'         : 'Серпня',
		'Sep'         : 'Вересень',
		'Oct'         : 'Жовтня',
		'Nov'         : 'Листопад',
		'Dec'         : 'Грудень',
		'Today'       : 'Сьогодні',
		'Yesterday'   : 'Вчора',
		/* mimetypes */
		'Unknown'                           : 'Невідомий',
		'Folder'                            : 'Папка',
		'Alias'                             : 'Посилання',
		'Broken alias'                      : 'Бита посилання',
		'Plain text'                        : 'Звичайний текст',
		'Postscript document'               : 'Документ postscript',
		'Application'                       : 'Додаток',
		'Microsoft Office document'         : 'Документ Microsoft Office',
		'Microsoft Word document'           : 'Документ Microsoft Word',  
		'Microsoft Excel document'          : 'Документ Microsoft Excel',
		'Microsoft Powerpoint presentation' : 'Презентація Microsoft Powerpoint',
		'Open Office document'              : 'Документ Open Office',
		'Flash application'                 : 'Додаток Flash',
		'XML document'                      : 'Документ XML',
		'Bittorrent file'                   : 'Bittorrent файл',
		'7z archive'                        : 'Архів 7z',
		'TAR archive'                       : 'Архів TAR',
		'GZIP archive'                      : 'Архів GZIP',
		'BZIP archive'                      : 'Архів BZIP',
		'ZIP archive'                       : 'Архів ZIP',
		'RAR archive'                       : 'Архів RAR',
		'Javascript application'            : 'Додаток Javascript',
		'PHP source'                        : 'Исходник PHP',
		'HTML document'                     : 'Документ HTML',
		'Javascript source'                 : 'Исходник Javascript',
		'CSS style sheet'                   : 'Таблиця стилів CSS',
		'C source'                          : 'Исходник C',
		'C++ source'                        : 'Исходник C++',
		'Unix shell script'                 : 'Скрипт Unix shell',
		'Python source'                     : 'Исходник Python',
		'Java source'                       : 'Исходник Java',
		'Ruby source'                       : 'Исходник Ruby',
		'Perl script'                       : 'Скрипт Perl',
		'BMP image'                         : 'Зображення BMP',
		'JPEG image'                        : 'Зображення JPEG',
		'GIF Image'                         : 'Зображення GIF',
		'PNG Image'                         : 'Зображення PNG',
		'TIFF image'                        : 'Зображення TIFF',
		'TGA image'                         : 'Зображення TGA',
		'Adobe Photoshop image'             : 'Зображення Adobe Photoshop',
		'MPEG audio'                        : 'Аудіо MPEG',
		'MIDI audio'                        : 'Аудіо MIDI',
		'Ogg Vorbis audio'                  : 'Аудіо Ogg Vorbis',
		'MP4 audio'                         : 'Аудіо MP4',
		'WAV audio'                         : 'Аудіо WAV',
		'DV video'                          : 'Відео DV',
		'MP4 video'                         : 'Відео MP4',
		'MPEG video'                        : 'Відео MPEG',
		'AVI video'                         : 'Відео AVI',
		'Quicktime video'                   : 'Відео Quicktime',
		'WM video'                          : 'Відео WM',
		'Flash video'                       : 'Відео Flash',
		'Matroska video'                    : 'Відео Matroska',
		// 'Shortcuts' : 'Клавиши',		
		'Select all files' : 'Виділити всі файли',
		'Copy/Cut/Paste files' : 'Копіювати/Вирізати/Вставити файли',
		'Open selected file/folder' : 'Відкрити папку/файл',
		'Open/close QuickLook window' : 'Відкрити/закрити вікно швидкого перегляду',
		'Remove selected files' : 'Видалити виділені файли',
		'Selected files or current directory info' : 'Інформація про виділених файлів або поточній папці',
		'Create new directory' : 'Нова папка',
		'Open upload files form' : 'Відкрити вікно завантаження файлів',
		'Select previous file' : 'Вибрати попередній файл',
		'Select next file' : 'Вибрати наступний файл',
		'Return into previous folder' : 'Повернутися в попередню папку',
		'Increase/decrease files selection' : 'Збільшити/зменшити виділення файлів',
		'Authors'                       : 'Автори',
		'Sponsors'  : 'Спонсори',
		'elFinder: Web file manager'    : 'elFinder: Файловий менеджер для Web',
		'Version'                       : 'Версія',
		'Copyright: Studio 42 LTD'      : 'Copyright: Студия 42',
		'Donate to support project development' : 'Підтримайте розробку',
		'Javascripts/PHP programming: Dmitry (dio) Levashov, dio@std42.ru' : 'Програмування Javascripts/php: Дмитрий (dio) Левашов, dio@std42.ru',
		'Python programming, techsupport: Troex Nevelin, troex@fury.scancode.ru' : 'Програмування Python, техподдержка: Troex Nevelin, troex@fury.scancode.ru',
		'Design: Valentin Razumnih'     : 'Дизайн: Валентин Разумных',
		'Spanish localization'          : 'Испанская локализация',
		'Icons' : 'Иконки',
		'License: BSD License'          : 'Ліцензія: BSD License',
		'elFinder documentation'        : 'Документація elFinder',
		'Simple and usefull Content Management System' : 'Проста і зручна Система Управління Сайтами',
		'Support project development and we will place here info about you' : 'Підтримайте розробку продукту і ми розмістимо тут інформацію про вас.',
		'Contacts us if you need help integrating elFinder in you products' : 'Якщо ви хочете інтегрувати elFinder в свій продукт, звертайтеся до нас',
		'helpText' : 'elFinder працює аналогічно до файлового менеджера у вашому комп&rsquo;ютері. <br /> Маніпулювати файлами можна за допомогою кнопок на верхній панелі, контекстного меню або сполучення клавіш. Щоб перемістити файли/папки, просто перенесіть їх на іконку потрібної папки. Якщо буде затиснута клавіша Shift файли будуть скопійовані. <br/> <br/> ElFinder підтримує наступні сполучення клавіш:'
		
	};
	
})(jQuery);