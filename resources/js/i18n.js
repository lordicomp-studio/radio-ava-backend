import {createI18n} from "vue-i18n";

export default createI18n({
    locale: 'fa',
    fallbackLocale: 'en',
    messages: {
        en: {
            toasts: {
                success: 'Success',
                error: 'Error',
                createdSuccessfully: 'Item created successfully',
                editedSuccessfully: 'Item edited successfully',
                deletedSuccessfully: 'Item deleted successfully',
                confirmedSuccessfully: 'Item confirmed successfully',
                rejectedSuccessfully: 'Item rejected successfully',
                revertedSuccessfully: 'Item reverted successfully',

                formDataFlawed: 'Form data is flawed',

                confirmation: 'Confirmation',
                areYouSure: 'Are you sure?',
                yes: 'yes',
                no: 'no',
            },
            auth: {
                logout: 'logout'
            },
            pages: {
                Dashboard: 'Dashboard', // sidebar item
                Users: 'Users', // sidebar item
                UsersList: 'Users List', // sidebar item
                CreateUser: 'Create User', // sidebar item
                EditUser: 'Edit User',
                Payments: 'Payments', // sidebar item
                ActivityLogs: 'Activity Logs', // sidebar item
                Views: 'Views', // sidebar item
                DownloadRecords: 'Download Records', // sidebar item
                Categories: 'Categories', // sidebar item
                Tags: 'Tags', // sidebar item
                Comments: 'Comments', // sidebar item
                Tickets: 'Tickets', // sidebar item
                TicketDetails: 'Ticket Details',
                Pages: 'Pages', // sidebar item
                PagesList: 'Pages List', // sidebar item
                CreatePage: 'Create Page', // sidebar item
                EditPage: 'Edit Page',
                Articles: 'Articles', // sidebar item
                ArticlesList: 'Articles List', // sidebar item
                CreateArticle: 'Create Article', // sidebar item
                EditArticle: 'Edit Article',
                Products: 'Products', // sidebar item
                ProductsList: 'Products List', // sidebar item
                CreateProduct: 'Create Product', // sidebar item
                EditProduct: 'Edit Product',
                ConfirmProduct: 'Confirm Product',
                ProductEditRequests: 'Product Edit Requests', // sidebar item
                ReviewProductEditRequest: 'Review Product Edit Request',
                FileManager: 'File Manager', // sidebar item
                Gallery: 'Gallery', // sidebar item
                PermissionRoles: 'Permission-Roles', // sidebar item
                Roles: 'Roles', // sidebar item
                RolesDetails: 'Roles Details',
                Permissions: 'Permissions', // sidebar item
                Settings: 'Settings', // sidebar item
                GeneralSettings: 'General', // sidebar item
                SEOSettings: 'SEO', // sidebar item
                MenusSettings: 'Menus', // sidebar item
                ThemeSettings: 'Theme', // sidebar item
                Documents: 'Documents', // sidebar item
                Discounts: 'Discounts', // sidebar item
                DiscountsList: 'Discounts List', // sidebar item
                CreateDiscount: 'Create Discount', // sidebar item
                EditDiscount: 'Edit Discount',
                DiscountCodes: 'Discount Codes', // sidebar item
            },
            fields: {
                ID: 'ID',
                causer: 'Causer',
                event: 'Event',
                subjectType: 'Subject Type',
                subject: 'Subject',
                createdAt: 'Created At',
                actions: 'Actions',
                cover: 'Cover',
                title: 'Title',
                writer: 'Writer',
                name: 'Name',
                creator: 'Creator',
                sender: 'Sender',
                product: 'Product',
                article: 'Article',
                status: 'Status',
                text: 'Text',
                downloader: 'Downloader',
                price: 'Price',
                code: 'Code',
                buyer: 'Buyer',
                trackId: 'Track ID',
                priority: 'Priority',
                receiver: 'Receiver',
                user: 'User',
                level: 'Level',
                role: 'Role',
                '2FAStatus': '2FA status',
                viewer: 'Viewer',
                IP: 'IP',
                device: 'Device',
                platform: 'Platform',
                browser: 'Browser',
                privilegedRoles: 'Privileged Roles',
                percent: 'Percent',
                expirationDate: 'Expiration Date',
                size: 'Size',
                lastChange: 'Last Change',
                uploader: 'Uploader',
            },
            tables: {
                common: {
                    search: 'search',
                    actions: 'actions',
                    show: 'show',
                    edit: 'Edit',
                    delete: 'delete',
                    deleteAll: 'delete all',
                    add: 'Add',
                    unavailable: 'unavailable',
                    filter: 'filter',
                },
                // components
                paginationComponent: {
                    show: 'show',
                    to: 'to',
                    from: 'from',
                    results: 'results',
                },
                // pages
                users: {
                    active: 'active',
                    inactive: 'inactive',
                },
                comments: {
                    rejected: 'rejected',
                    accepted: 'accepted',
                },
                fileManager: {
                    totalSpace: 'total space',
                    freeSpace: 'free space',
                    files: 'Files',
                    settings: 'Settings',
                    copy: 'copy',
                    move: 'move',
                    paste: 'paste',
                    newFolder: 'new folder',
                    uploadFile: 'upload file',
                    items: 'items',
                    root: 'root',
                    download: 'download',
                    rename: 'rename',
                },
                gallery: {
                    showDetails: 'Show details',
                    uploadMedia: 'Upload media',
                    // filters
                    all: 'all',
                    uploadTime: 'upload time',
                    fileType: 'file type',
                    lastYear: 'last year',
                    lastMonth: 'last month',
                    lastDay: 'last day',
                    photo: 'photo',
                    video: 'video',
                    deleted: 'deleted',
                },
                roles: {
                    numberOfUsersWhoHaveThisRole: 'number of users who have this role',
                    roleUsers: "Role's Users",
                },
            },
            forms: {
                common: {
                    save: 'Save',
                    edit: 'Edit',
                    close: 'Close',
                    revert: 'Revert',
                    choose: 'choose',
                    picture: 'picture',
                    uploadPicture: "upload @:{'forms.common.picture'}",
                    name: "name",
                    title: "title",
                    slug: "slug",
                    description: "description",
                    text: 'text',
                    change: 'change',
                },
                // components
                mediumPicker: {
                    header: "{title}'s picture",
                    comment: "Choose {title}'s picture",
                },
                tagCategorySelector: {
                    comment: "Choose {title}",
                },
                publishTimeHandler: {
                    publishTime: 'publish time',
                    realPublishTime: 'real publish time',
                    publishTimeComment: 'you can choose a custom publish time',
                },
                filePicker: {
                    selectFile: 'select file...'
                },
                // pages
                users: {
                    user: 'user',
                    name: 'username',
                    fname: 'first name',
                    lname: 'last name',
                    email: 'email',
                    password: 'password',
                    level: 'level',
                    levelComment: "Choose user's level",
                    role: 'role',
                    roleComment: "Choose user's role",
                    normalUser: 'Normal user',
                },
                categories: {
                    category: 'category',
                },
                tags: {
                    tag: 'tag',
                },
                comments: {
                    comment: 'comment',
                    status: 'status',
                },
                tickets: {
                    ticket: 'ticket',
                    sender: "sender",
                    receiver: "receiver",
                    priority: "priority",
                    status: "status",
                    low: 'low',
                    medium: 'medium',
                    high: 'high',
                    closed: 'closed',
                    open: 'open',
                    // show
                    file: 'file',
                    editMessage: 'edit message',
                    enterYourMessage: 'Enter your message',
                    fileUnavailable: 'File unavailable',
                    unseen: 'unseen',
                    seenAt: 'seen at',
                },
                pages: {
                    page: 'page',
                },
                articles: {
                    article: 'article',
                    draft: 'draft',
                    published: 'published',
                },
                products: {
                    product: 'product',
                    requested: 'requested',
                    file: 'file',
                    version: 'version',
                    previewUrl: 'preview url',
                    price: 'price',
                    features: 'features',
                    addFeature: 'add feature',
                    // Unchecked: 'unchecked', currently product status is sent from backend
                    // Pending: 'pending',
                    // Confirmed: 'confirmed',
                    // Rejected: 'rejected',
                    // Suspended: 'suspended',
                    requestedName: "@:{'forms.products.requested'} @:{'forms.common.name'}",
                    requestedSlug: "@:{'forms.products.requested'} @:{'forms.common.slug'}",
                    requestedFile: "@:{'forms.products.requested'} @:{'forms.products.file'}",
                    requestedVersion: "@:{'forms.products.requested'} @:{'forms.products.version'}",
                    requestedPreviewUrl: "@:{'forms.products.requested'} @:{'forms.products.previewUrl'}",
                    requestedPrice: "@:{'forms.products.requested'} @:{'forms.products.price'}",
                    requestedDescription: "@:{'forms.products.requested'} @:{'forms.common.description'}",
                    requestedText: "@:{'forms.products.requested'} @:{'forms.common.text'}",
                    requestedPicture: "@:{'forms.products.requested'} @:{'forms.common.picture'}",
                },
                discountCodes: {
                    discountCode: 'discount code',
                    code: 'code',
                    percent: 'percent',
                    expirationDate: 'expiration date'
                },
                fileManager: {
                    chooseFile: 'choose file',
                    createNewFolder: 'Create New Folder',
                    pleaseChooseAFile: 'Please choose a file',
                    uploadIsInProgress: 'Upload is in progress',
                    upload: 'upload',
                    // fileManager settings
                    allowedUploadFormats: 'allowed upload formats',
                    maxUploadSize: 'maximum upload size',
                    galleryUploadFolderStructure: 'gallery uploads folder structure'
                },
                roles: {
                    role: 'role',
                    color: 'color',
                    rolePermission: "role's permission",
                },
                permissions: {
                    permission: 'permission',
                    parentPermission: 'parent permission',
                },
                generalSettings: {
                    websiteTitle: 'website title',
                    websiteTitleComment: "Enter your website's title.",
                    websiteDescription: 'website description',
                    websiteDescriptionComment: 'Briefly describe the activity on the website.',
                    debugMode: 'debug mode',
                    debugModeComment: 'Using this mode puts your site at security risk. Never enable debug mode without consulting specialists.',
                    logo: 'logo',
                    favicon: 'favicon',
                },
                seoSettings: {
                    general: 'General',
                    titleTag: 'title tag',
                    descriptionTag: 'description tag',
                    similarToGeneralSettings: 'similar to general settings',
                    openGraphProtocol: 'Open Graph Protocol',
                    protocolTitle: 'protocol title',
                    protocolDescription: 'protocol description',
                    protocolPhoto: 'protocol photo',
                },
                menuSettings: {
                    menu: 'menu',
                    editName: 'edit name',
                    chooseAMenuOrSubmenu: 'Please select one of the checkboxes next to the menus or submenus.',
                    menuTitle: 'menu title(for show)',
                    menuName: 'menu name(for inner system)',
                    addCustomSubmenu: 'add custom submenu',
                    addSubmenu: 'Add submenu',
                    editSubmenu: 'Edit submenu',
                    link: 'link',
                },
                themeSettings: {
                    chosenProduct: 'chosen product',
                    specialProducts: 'special products',
                },
            },
        },
        fa: {
            toasts: {
                success: 'موفقیت!',
                error: 'حطا!',
                createdSuccessfully: 'دیتا با موفقیت ایجاد شد',
                editedSuccessfully: 'دیتا با موفقیت ویرایش شد',
                deletedSuccessfully: 'دیتا با موفقیت حذف شد',
                confirmedSuccessfully: 'دیتا با موفقیت تایید شد',
                rejectedSuccessfully: 'دیتا با موفقیت رد شد',
                revertedSuccessfully: 'دیتا با موفقیت بازگردانی شد',

                formDataFlawed: 'اطلاعات فرم ناقس است!',

                confirmation: 'تاییدیه',
                areYouSure: 'آیا مطمئن هستید؟',
                yes: 'بله',
                no: 'خیر',
            },
            auth: {
                logout: 'حروج از حساب'
            },
            pages: {
                Dashboard: 'داشبورد',
                Users: 'کاربران',
                UsersList: 'نمایش همه کاربران',
                CreateUser: 'افزودن کاربر جدید',
                EditUser: 'ویرایش کاربر',
                Payments: 'پرداخت ها',
                ActivityLogs: 'لاگ فعالیت',
                Views: 'بازدید ها',
                DownloadRecords: 'دانلود ها',
                Categories: 'دسته‌بندی ها',
                Tags: 'برچسب ها',
                Comments: 'دیدگاه ها',
                Tickets: 'تیکت ها',
                TicketDetails: 'جزییات تیکت',
                Pages: 'برگه ها',
                PagesList: 'نمایش همه برگه ها',
                CreatePage: 'افزودن برگه جدید',
                EditPage: 'ویرایش برگه',
                Articles: 'مقاله ها',
                ArticlesList: 'نمایش همه مقاله ها',
                CreateArticle: 'افزودن مقاله جدید',
                EditArticle: 'ویرایش مقاله',
                Products: 'محصولات',
                ProductsList: 'نمایش همه محصولات',
                CreateProduct: 'افزودن محصول جدید',
                EditProduct: 'ویرایش محصول',
                ConfirmProduct: 'تایید محصول',
                ProductEditRequests: 'درخواست های ویرایش محصول',
                ReviewProductEditRequest: 'بررسی درخواست ویرایش محصول',
                FileManager: 'مدیریت فایل ها',
                Gallery: 'گالری',
                PermissionRoles: 'سطوح دسترسی',
                Roles: 'مقام ها',
                RolesDetails: 'جزییات مقام',
                Permissions: 'دسترسی ها',
                Settings: 'تنظیمات',
                GeneralSettings: 'تنظیمات عمومی',
                SEOSettings: 'تنظیمات سئو',
                MenusSettings: 'تنظیمات منو ها',
                ThemeSettings: 'تنظیمات قالب',
                Documents: 'مستندات',
                Discounts: 'تخفیف ها', // sidebar item
                DiscountsList: 'نمایش همه تخفیف ها', // sidebar item
                CreateDiscount: 'افزودن تخفیف جدید', // sidebar item
                EditDiscount: 'ویرایش تخفیف',
                DiscountCodes: 'کد های تخفیف', // sidebar item
            },
            fields: {
                ID: 'آیدی',
                causer: 'انجام دهنده',
                event: 'رویداد',
                subjectType: 'نوع مفعول',
                subject: 'مفعول',
                createdAt: 'تاریخ ساخت',
                actions: 'عملیات',
                cover: 'تصویر',
                title: 'عنوان',
                writer: 'نویسنده',
                name: 'نام',
                creator: 'سازنده',
                sender: 'فرستنده',
                product: 'محصول',
                article: 'مقاله',
                status: 'وضعیت',
                text: 'متن',
                downloader: 'دانلود کننده',
                price: 'قیمت',
                code: 'کد',
                buyer: 'خریدار',
                trackId: 'کد رهگیری',
                priority: 'اولویت',
                receiver: 'دریافت کننده',
                user: 'کاربر',
                level: 'مقام',
                role: 'سطح دسترسی',
                '2FAStatus': 'لاگین دومرحله ای',
                viewer: 'بازدید کننده',
                IP: 'آی پی',
                device: 'دستگاه',
                platform: 'پلتفرم',
                browser: 'مرورگر',
                privilegedRoles: 'دارندگان دسترسی',
                percent: 'درصد',
                expirationDate: 'تاریخ انقضا',
                size: 'سایز',
                lastChange: 'آخرین تغییر',
                uploader: 'آپلود کننده',
            },
            tables: {
                common: {
                    search: 'جستجو',
                    actions: 'عملیات',
                    show: 'نمایش',
                    edit: 'ویرایش',
                    delete: 'حذف',
                    deleteAll: 'حذف همه',
                    add: 'افزودن',
                    unavailable: 'ناموجود',
                    filter: 'فیلتر',
                },
                // components
                paginationComponent: {
                    show: 'نمایش',
                    to: 'تا',
                    from: 'از',
                    results: 'نتیجه',
                },
                // pages
                users: {
                    active: 'فعال',
                    inactive: 'غیر فعال',
                },
                comments: {
                    rejected: 'رد شده',
                    accepted: 'تایید شده',
                },
                fileManager: {
                    totalSpace: 'فضای کل',
                    freeSpace: 'فضای آزاد',
                    files: 'فایل ها',
                    settings: 'تنظیمات',
                    copy: 'کپی',
                    move: 'جابجایی',
                    paste: 'چسباندن',
                    newFolder: 'پوشه جدید',
                    uploadFile: 'آپلود فایل',
                    items: 'آیتم',
                    root: 'ریشه',
                    download: 'دانلود',
                    rename: 'تغییر نام',
                },
                gallery: {
                    showDetails: 'نمایش جزئیات',
                    uploadMedia: 'آپلود رسانه',
                    // filters
                    all: 'همه',
                    uploadTime: 'زمان آپلود',
                    fileType: 'نوع فایل',
                    lastYear: 'سال گذشته',
                    lastMonth: 'ماه گذشته',
                    lastDay: 'روز گذشته',
                    photo: 'عکس',
                    video: 'ویدیو',
                    deleted: 'حذف شده',
                }
            },
            forms: {
                common: {
                    save: 'ذخیره',
                    edit: 'ویرایش',
                    close: 'بستن',
                    revert: 'بازگردانی',
                    choose: 'انتخاب کنید',
                    picture: 'تصویر',
                    uploadPicture: "آپلود @:{'forms.common.picture'}",
                    name: "نام",
                    title: "عنوان",
                    slug: "پیوند یکتا",
                    description: "توضیحات",
                    text: 'متن',
                    change: 'تغییر',
                },
                // components
                mediumPicker: {
                    header: "تصویر {title}",
                    comment: "تصویر {title} را تنظیم کنید.",
                },
                tagCategorySelector: {
                    categories: 'دسته‌بندی ها',
                    tags: 'برچسب ها',
                    comment: "انتخاب {title}",
                },
                publishTimeHandler: {
                    publishTime: 'زمان انتشار',
                    realPublishTime: 'زمان انتشار حقیقی',
                    publishTimeComment: 'میتوانید از زمان انتشار اختیاری برای چیدمان انتخاب کنید.',
                },
                filePicker: {
                    selectFile: 'انتخاب فایل...'
                },
                // pages
                users: {
                    user: 'کاربر',
                    name: 'نام کاربری',
                    fname: 'نام کوچک',
                    lname: 'نام خانوادگی',
                    email: 'ایمیل',
                    password: 'کلمه عبور',
                    level: 'مقام',
                    levelComment: "مقام کاربر را انتخاب کنید",
                    role: 'سطح دسترسی',
                    roleComment: "سطح دسترسی کاربر را انتخاب کنید",
                    normalUser: 'کاربر عادی',
                },
                categories: {
                    category: 'دسته‌بندی',
                },
                tags: {
                    tag: 'برچسب',
                },
                comments: {
                    comment: 'دیدگاه',
                    text: 'متن',
                    status: 'وضعیت',
                },
                tickets: {
                    ticket: 'تیکت',
                    sender: "فرستنده",
                    receiver: "دریافت کننده",
                    priority: "اولویت",
                    status: "وضعیت",
                    low: 'پایین',
                    medium: 'متوسط',
                    high: 'بالا',
                    closed: 'بسته',
                    open: 'باز',
                    // show
                    file: 'فایل',
                    editMessage: 'ویرایش پیام',
                    enterYourMessage: 'پیام خود را وارد کنید',
                    fileUnavailable: 'فایل ناموجود',
                    unseen: 'دیده نشده',
                    seenAt: 'دیده شده در',
                },
                pages: {
                    page: 'برگه',
                },
                articles: {
                    article: 'مقاله',
                    draft: 'پیشنویس',
                    published: 'منتشر شده',
                },
                products: {
                    product: 'محصول',
                    requested: 'درخواستی',
                    file: 'فایل',
                    version: 'ورژن',
                    previewUrl: 'آدرس پیشنمایش',
                    price: 'قیمت',
                    features: 'ویژگی ها',
                    addFeature: 'افزودن ویژگی',
                    // Unchecked: 'unchecked', currently product and productEditRequest status is sent from backend
                    // Pending: 'pending',
                    // Confirmed: 'confirmed',
                    // Rejected: 'rejected',
                    // Suspended: 'suspended',
                    requestedName: "@:{'forms.common.name'} @:{'forms.products.requested'}",
                    requestedSlug: "@:{'forms.common.slug'} @:{'forms.products.requested'}",
                    requestedFile: "@:{'forms.products.file'} @:{'forms.products.requested'}",
                    requestedVersion: "@:{'forms.products.version'} @:{'forms.products.requested'}",
                    requestedPreviewUrl: "@:{'forms.products.previewUrl'} @:{'forms.products.requested'}",
                    requestedPrice: "@:{'forms.products.price'} @:{'forms.products.requested'}",
                    requestedDescription: "@:{'forms.common.description'} @:{'forms.products.requested'}",
                    requestedText: "@:{'forms.common.text'} @:{'forms.products.requested'}",
                    requestedPicture: "@:{'forms.common.picture'} @:{'forms.products.requested'}",
                },
                discountCodes: {
                    discountCode: 'کد تخفیف',
                    code: 'کد',
                    percent: 'درصد',
                    expirationDate: 'تاریخ انقضا'
                },
                fileManager: {
                    createNewFolder: 'ایجاد پوشه جدید',
                    chooseFile: 'انتخاب فایل',
                    pleaseChooseAFile: 'آپلود در جریان است',
                    uploadIsInProgress: 'Upload is in progress',
                    upload: 'آپلود',
                },
                roles: {
                    role: 'مقام',
                    color: 'رنگ',
                    rolePermission: "دسترسی های مقام",
                },
                permissions: {
                    permission: 'دسترسی',
                    parentPermission: 'دسترسی مادر',
                },
                generalSettings: {
                    websiteTitle: 'عنوان سایت',
                    websiteTitleComment: "عنوان سایت خود را وارد کنید.",
                    websiteDescription: 'توضیحات سایت',
                    websiteDescriptionComment: 'به طور مختصر نوع فعالیت سایت را شرح دهید.',
                    debugMode: 'حالت دیباگ',
                    debugModeComment: 'استفاده از این حالت سایت شما را مورد خطر امینیتی قرار میدهد. هرگز بدون مشاوره متخصصین حالت دیباگ را فعال نکنید.',
                    logo: 'لوگو',
                    favicon: 'فاوآیکون',
                },
                seoSettings: {
                    general: 'عمومی',
                    titleTag: 'تگ title',
                    descriptionTag: 'تگ description',
                    similarToGeneralSettings: 'مشابه تنظیمات عمومی',
                    openGraphProtocol: 'پروتکل open graph',
                    protocolTitle: 'عنوان پروتکل',
                    protocolDescription: 'توضیحات پروتکل',
                    protocolPhoto: 'تصویر پروتکل',
                },
                menuSettings: {
                    menu: 'منو',
                    editName: 'ویرایش نام',
                    chooseAMenuOrSubmenu: 'لطفا یکی از تیک های کنار منو ها یا زیرمنو ها را انتخاب کنید.',
                    addCustomSubmenu: 'افزودن زیرمنوی سفارشی',
                    menuTitle: 'عنوان منو(برای نمایش)',
                    menuName: 'نام منو(برای درون سیستم)',
                    addSubmenu: 'افزودن زیرمنو',
                    editSubmenu: 'ویرایش زیرمنو',
                    link: 'لینک',
                },
                themeSettings: {
                    chosenProduct: 'محصول برگزیده',
                    specialProducts: 'محصولات ویژه',
                },
            },
        },
    }
});
