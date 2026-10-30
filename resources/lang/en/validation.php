<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Validation Language Lines
    |--------------------------------------------------------------------------
    |
    | The following language lines contain the default error messages used by
    | the validator class. Some of these rules have multiple versions such
    | as the size rules. Feel free to tweak each of these messages here.
    |
    */

    'accepted'             => 'The :attribute must be accepted.',
    'active_url'           => 'The :attribute is not a valid URL.',
    'after'                => 'The :attribute must be a date after :date.',
    'after_or_equal'       => 'The :attribute must be a date after or equal to :date.',
    'alpha'                => 'The :attribute may only contain letters.',
    'alpha_dash'           => 'The :attribute may only contain letters, numbers, and dashes.',
    'alpha_num'            => 'The :attribute may only contain letters and numbers.',
    'array'                => 'The :attribute must be an array.',
    'before'               => 'The :attribute must be a date before :date.',
    'before_or_equal'      => 'The :attribute must be a date before or equal to :date.',
    'between'              => [
        'numeric' => 'The :attribute must be between :min and :max.',
        'file'    => 'The :attribute must be between :min and :max kilobytes.',
        'string'  => 'The :attribute must be between :min and :max characters.',
        'array'   => 'The :attribute must have between :min and :max items.',
    ],
    'boolean'              => 'The :attribute field must be true or false.',
    'confirmed'            => 'The :attribute confirmation does not match.',
    'date'                 => 'The :attribute is not a valid date.',
    'date_format'          => 'The :attribute does not match the format :format.',
    'different'            => 'The :attribute and :other must be different.',
    'digits'               => 'The :attribute must be :digits digits.',
    'digits_between'       => 'The :attribute must be between :min and :max digits.',
    'dimensions'           => 'The :attribute has invalid image dimensions.',
    'distinct'             => 'The :attribute field has a duplicate value.',
    'email'                => 'The :attribute must be a valid email address.',
    'exists'               => 'The selected :attribute is invalid.',
    'file'                 => 'The :attribute must be a file.',
    'filled'               => 'The :attribute field must have a value.',
    'image'                => 'The :attribute must be an image.',
    'in'                   => 'The selected :attribute is invalid.',
    'in_array'             => 'The :attribute field does not exist in :other.',
    'integer'              => 'The :attribute must be an integer.',
    'ip'                   => 'The :attribute must be a valid IP address.',
    'ipv4'                 => 'The :attribute must be a valid IPv4 address.',
    'ipv6'                 => 'The :attribute must be a valid IPv6 address.',
    'json'                 => 'The :attribute must be a valid JSON string.',
    'max'                  => [
        'numeric' => 'The :attribute may not be greater than :max.',
        'file'    => 'The :attribute may not be greater than :max kilobytes.',
        'string'  => 'The :attribute may not be greater than :max characters.',
        'array'   => 'The :attribute may not have more than :max items.',
    ],
    'mimes'                => 'The :attribute must be a file of type: :values.',
    'mimetypes'            => 'The :attribute must be a file of type: :values.',
    'min'                  => [
        'numeric' => 'The :attribute must be at least :min.',
        'file'    => 'The :attribute must be at least :min kilobytes.',
        'string'  => 'The :attribute must be at least :min characters.',
        'array'   => 'The :attribute must have at least :min items.',
    ],
    'not_in'               => 'The selected :attribute is invalid.',
    'numeric'              => 'The :attribute must be a number.',
    'present'              => 'The :attribute field must be present.',
    'regex'                => 'The :attribute format is invalid.',
    'required'             => 'The :attribute field is required.',
    'required_if'          => 'The :attribute field is required when :other is :value.',
    'required_unless'      => 'The :attribute field is required unless :other is in :values.',
    'required_with'        => 'The :attribute field is required when :values is present.',
    'required_with_all'    => 'The :attribute field is required when :values is present.',
    'required_without'     => 'The :attribute field is required when :values is not present.',
    'required_without_all' => 'The :attribute field is required when none of :values are present.',
    'same'                 => 'The :attribute and :other must match.',
    'size'                 => [
        'numeric' => 'The :attribute must be :size.',
        'file'    => 'The :attribute must be :size kilobytes.',
        'string'  => 'The :attribute must be :size characters.',
        'array'   => 'The :attribute must contain :size items.',
    ],
    'string'               => 'The :attribute must be a string.',
    'timezone'             => 'The :attribute must be a valid zone.',
    'unique'               => 'The :attribute has already been taken.',
    'uploaded'             => 'The :attribute failed to upload.',
    'url'                  => 'The :attribute format is invalid.',

    /*
    |--------------------------------------------------------------------------
    | Custom Validation Language Lines
    |--------------------------------------------------------------------------
    |
    | Here you may specify custom validation messages for attributes using the
    | convention "attribute.rule" to name the lines. This makes it quick to
    | specify a specific custom language line for a given attribute rule.
    |
    */

    'custom' => [
        'attribute-name' => [
            'rule-name' => 'custom-message',
        ],

        'pwd' => [
            'required' => 'Password is require',
            'min' => 'Password require minimum of 6 characters',
        ],

        'email' => [
            'required' => 'Please fill in your email',
            'email' => 'Email format incorrect',
            'exists' => 'Email has not been registered',
        ],

        'g-recaptcha-response' => [
            'required' => 'Please ensure captcha is checked',
        ],

        'user_id' => [
            'required' => 'User ID not included',
            'exists' => 'User ID not found',
        ],

        'username' => [
            'required' => 'Please fill in your username',
            'unique' => 'Username has been registered to another account',
            'min' => 'Username require a minimum of 6 characters',
            'alpha_dash' => 'Username may only contain numbers, alphabets, dash(-) and underscore(_) only',
        ],

        'selected_pkg' => [
            'required' => 'Please select a package!',
        ],

		'selected_topup_pkg' => [
            'required' => 'Please select an existing package for topup!',
        ],

		'upoint_amount' => [
            'required' => 'Please include the amount of U-Point to puchase',
            'numeric' => 'Please include only numeric value for U-Point Amount',
        ],

		'upoint_receipt' => [
            'required' => 'Please include proof of payment!',
        ],

		'wth_amount' => [
            'required' => 'Please include withdrawal amount!',
            'min' => 'Withdrawal amount must be more than 0!',
            'numeric' => 'Please include only numeric value for withdrawal amount!',
        ],

		'wth_wallet' => [
            'required' => 'Please select a wallet to withdraw',
        ],

		'wth_id' => [
            'required' => 'Withdrawal ID Not Found!',
        ],

        'amount' => [
            'required' => 'Amount is required!',
            'numeric' => 'Amount\'s value can only be numeric!',
            'min' => 'Amount must be more than 0!',
        ],

        'mgr_type_selected' => [
            'required' => 'Please Select Manager Type',
            'numeric' => 'Please Select Manager Type',
            'min' => 'Please Select Manager Type',
        ],

        'bank_holder_name' => [
            'required' => 'Please fill in Bank Holder Name',
        ],

        'bank_acc_no' => [
            'required' => 'Please fill in Bank Acc No',
        ],

		'bank' => [
            'required' => 'Please select a bank',
            'numeric' => 'Please select a bank',
            'exists' => 'Selected bank does not exists',
        ],


        'old_pwd' => [
            'required' => 'Please fill in your current password',
        ],


        'new_pwd' => [
            'required' => 'Please fill in your new password',
            'min' => 'New password must have at least six(6) characters',
        ],


        'confirm_pwd' => [
            'required' => 'Please fill in your password confirmation',
            'same' => 'New Password and Confirm Password does not match!',
        ],

        'confirm_pwd' => [
            'required' => 'Please fill in your password confirmation',
            'same' => 'New Password and Confirm Password does not match!',
        ],

        'type' => [
            'required' => 'Please Select Document Type!',
            'numeric' => 'Please Select Document Type!',
            'min' => 'Please Select Document Type!',
        ],

        'approve_status' => [
            'required' => 'Please Select To Approve or Reject!',
            'numeric' => 'Please Select To Approve or Reject!',
            'min' => 'Please Select To Approve or Reject!',
            'max' => 'Please Select To Approve or Reject!',
        ],

        'topupAmount' => [
            'required' => 'Please fill in the amount you want to topup',
            'numeric' => 'Topup amount can only be a numeric value',
            'min' => 'Minimum topup is USD100',
        ],

        'curr_pwd' => [
            'required' => 'Please fill in your current password',
        ],

        'security_pwd' => [
            'required' => 'Please fill in your security password',
        ],

        'member_id' => [
            'required' => 'Please fill in member field',
            'exists' => 'Member does not exists',
        ],

        'member_type' => [
            'required' => 'Please select a rank',
            'numeric' => 'Please select a rank',
        ],

        'new_referral_id' => [
            'required' => 'Please fill in new referral field',
            'exists' => 'Selected referral does not exists',
        ],

        'referral_id' => [
            'required' => 'Please fill in referral',
            'exists' => 'Selected referral does not exists',
        ],

        'start_date' => [
            'required' => 'Please fill in start date',
            'date_format' => 'Invalid date format. The correct date format is:Y/m/d',
        ],

		'end_date' => [
            'required' => 'Please fill in end date',
            'date_format' => 'Invalid date format. The correct date format is:Y/m/d',
        ],

        'receipt' => [
            'required' => 'Please include a copy of your payment receipt',
        ],

        'code' => [
            'required' => 'Please fill in verification code',
        ],

        'product_name_en' => [
            'required' => 'Please fill in product name in English',
        ],

        'product_name_ch' => [
            'required' => 'Please fill in product name in Chinese',
        ],

        'product_name_bm' => [
            'required' => 'Please fill in product name in Bahasa Melayu',
        ],

        'collection' => [
            'required' => 'Please select a collection',
            'numeric' => 'Please select a collection',
            'min' => 'Please select a collection',
        ],

		'category' => [
            'required' => 'Please select a category',
            'numeric' => 'Please select a category',
            'min' => 'Please select a category',
        ],


        'cust_price' => [
            'required' => 'Please fill in product price for customer',
        ],

        'silver_price' => [
            'required' => 'Please fill in product price for silver member',
        ],

		'gold_price' => [
            'required' => 'Please fill in product price for gold member',
        ],

		'diamond_price' => [
            'required' => 'Please fill in product price for diamond member',
        ],

		'platinum_price' => [
            'required' => 'Please fill in product price for platinum member',
        ],


        'url_code' => [
            'required' => 'Please fill in product url code',
            'max' => 'Url code may contain a maximum of 25 characters only',
            'unique' => 'Url code has been used',
        ],

        'product_desc_en' => [
            'required' => 'Please fill in product description in English',
        ],

        'product_desc_ch' => [
            'required' => 'Please fill in product description in Chinese',
        ],

        'product_desc_bm' => [
            'required' => 'Please fill in product description in Bahase Melayu',
        ],


        'main_img' => [
            'required' => 'Please include a main image for product',
        ],

        'product_id' => [
            'required' => 'Product ID not found',
            'exists' => 'Product ID not found',
        ],

        'terms' => [
            'required' => 'Please agree to our Terms & Conditions',
        ],

        'addr1' => [
            'required' => 'Please fill in Address Line 1',
        ],

        'postcode' => [
            'required' => 'Please fill in postcode',
        ],

        'city' => [
            'required' => 'Please fill in city',
        ],

        'contact_no' => [
            'required' => 'Please fill in contact no',
            'numeric' => 'Contact No may contain numeric value only',
            'min' => 'Contact No must contains a minimum of 10 digits',
            'max' => 'Contact No may have a maximum of 12 digits only',
        ],


		'delivery_addr1' => [
            'required' => 'Please fill in Address Line 1',
        ],

        'delivery_postcode' => [
            'required' => 'Please fill in postcode',
        ],

        'delivery_city' => [
            'required' => 'Please fill in city',
        ],


        'delivery_state' => [
            'required' => 'Please select a state',
            'numeric' => 'Please select a state',
            'min' => 'Please select a state',
        ],

        'discount_amount' => [
            'required_if' => 'Please specify discount amount',
        ],

        'delivery_email' => [
            'required' => 'Please include email to receive order confirmation',
            'email' => 'Invalid email format',
        ],

        'receiver' => [
            'receiver' => 'Please include receiver name',
        ],

        'discount_id' => [
            'required' => 'Discount not found',
            'exists' => 'Discount not found',
        ],

        'discount_code' => [
            'required' => 'Please fill in your discount code',
            'exists' => 'Discount code does not exists',
            'unique' => 'Discount code has been used',
        ],

        'discount_amount' => [
            'required' => 'Please fill in discount amount',
            'numeric' => 'Discount amount can only contain numeric values',
            'min' => 'Discount amount must be more than 0',
        ],

        'discount_type' => [
            'required' => 'Please select a discount type',
            'numeric' => 'Please select a discount type',
            'min' => 'Please select a discount type',
            'max' => 'Please select a discount type',
        ],

        'expire_date' => [
            'required' => 'Please fill in expiry date',
            'date_format' => 'Expiry date format incorrect. Please check date format: Y/m/d',
        ],

        'discount_max_usage' => [
            'required' => 'Please fill in max usage',
            'numeric' => 'Max usage may only contain numeric value',
            'min' => 'Min max usage is 1',
        ],

        'discount_rule' => [
            'required' => 'Please select usage rule',
        ],


        'cat_name' => [
            'required' => 'Please fill in category name',
        ],

        'cat_code' => [
            'required' => 'Please fill in category code',
            'alpha_dash' => 'Category code may only contain numbers, characters, underscore(_) or dash(-)',
            'unique' => 'Category code has been used for another category',
        ],

        'subject' => [
            'required' => 'Please fill in your subject of contact',
        ],

        'message' => [
            'required' => 'Please fill in your message of contact',
        ],

        'product_cost' => [
            'required' => 'Please fill in product cost',
            'min' => 'Please fill in product cost',
            'numeric' => 'Product cost may only contain numeric value',
        ],

        'cat_size_type' => [
            'required' => 'Please select a size type',
            'numeric' => 'Please select a size type',
            'min' => 'Please select a size type',
        ],

        'banner_img' => [
            'required' => 'Please include banner image',
            'mimes' => 'Banner file type must be jpeg or png only',
        ],

        'banner_id' => [
            'required' => 'Banner not found',
        ],

        'courier' => [
            'required' => 'Please fill in courier for delivery',
        ],

        'tracking_no' => [
            'required' => 'Please fill in tracking no for delivery',
        ],

        'review_text' => [
            'required' => 'Please provide message for your review',
        ],

        'review_product_id' => [
            'required' => 'Please select a product for review',
        ],

        'delivery_country' => [
            'required' => 'Please select a country',
            'min' => 'Please select a country',
            'numeric' => 'Please select a country',
        ],

        'selected_product' => [
            'required' => 'Please select a product',
            'exists' => 'Product does not exists',
        ],

        'selected_color' => [
            'required' => 'Please select a color',
            'numeric' => 'Please select a color',
            'min' => 'Please select a color',
        ],

        'selected_size' => [
            'required' => 'Please select a size',
            'numeric' => 'Please select a size',
            'min' => 'Please select a size',
        ],

        'qty' => [
            'required' => 'Please fill in require quantity',
            'numeric' => 'Please fill in require quantity',
            'min' => 'Please fill in require quantity',
        ],

        'price' => [
            'required' => 'Please fill in price',
            'numeric' => 'Please fill in price',
        ],

        'referral' => [
            'required' => 'Please fill in your referral',
            'exists' => 'Specify referral does not exists',
        ],

        'cat_main' => [
            'required' => 'Please select the main category',
            'numeric' => 'Please select the main category',
            'min' => 'Please select the main category',
        ],

        'placement' => [
            'required' => 'Please select member placement',
            'numeric' => 'Please select member placement',
            'min' => 'Please select member placement',
        ],

        'country' => [
            'required' => 'Please select a country for the address',
            'numeric' => 'Please select a country for the address',
            'min' => 'Please select a country for the address',
        ],

        'agree_term' => [
            'required' => 'Please agree to our Terms & Conditions to register',
            'numeric' => 'Please agree to our Terms & Conditions to register',
            'min' => 'Please agree to our Terms & Conditions to register',
        ],

        'wth_amount' => [
            'required' => 'Please fill in your withdrawal amount',
            'numeric' => 'Withdrawal amount only may contain numeric',
        ],

        'shop_id' => [
            'required' => 'Shop ID not found',
            'exists' => 'Shop ID not found',
        ],

        'voucher_qty' => [
            'required' => 'Please select voucher quantity',
            'numeric' => 'Please select voucher quantity',
            'min' => 'Please select voucher quantity',
        ],

        'voucher_shop' => [
            'required' => 'Please select voucher shop',
            'numeric' => 'Please select voucher shop',
            'exists' => 'Please select voucher shop',
        ],

        'receiver_id' => [
            'required' => 'Please fill in referral',
        ],

        'selected_type' => [
            'required' => 'Please select membership type',
            'numeric' => 'Please select membership type',
            'min' => 'Please select membership type',
        ],


        'et_title' => [
            'required' => 'Please fill in title',
        ],


        'et_desc' => [
            'required' => 'Please fill in description',
        ],


        'et_price' => [
            'required' => 'Please fill in price',
            'numeric' => 'Price may only contain numeric',
        ],

        'et_prebook_day' => [
            'required' => 'Please fill in Pre-Book Days',
            'numeric' => 'Pre-Book Days may only contain numeric',
        ],

        'et_main_img' => [
            'required' => 'Please include main image',
            'mimes' => 'Attached image can only be of type: JPG or PNG',
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Custom Validation Attributes
    |--------------------------------------------------------------------------
    |
    | The following language lines are used to swap attribute place-holders
    | with something more reader friendly such as E-Mail Address instead
    | of "email". This simply helps us make messages a little cleaner.
    |
    */

    'attributes' => [],

];
