<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;

use App\Enums\UserStatusEnum;
use App\Enums\UserTypeEnum;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use PHPOpenSourceSaver\JWTAuth\Contracts\JWTSubject;

class User extends Authenticatable implements JWTSubject
{
    use HasFactory,Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>;
     */
    protected $fillable = [
        'name',
        'username',
        'status',
        'user_type',
        'email',
        'contact_no',
        'password',
        'address_id',
        'nationality',
        'designation_id',
        'gender',
        'caste',
        'religion',
        'doj',
        'dob',
        'aadhaar_no',
        'pan_no',
        'passport_no',
        'language',
        'code',
        'bank_name',
        'account_holder_name',
        'bank_account_no',
        'bank_ifsc',
        'bank_branch',
        'upi',
        'parent_id',
        'manager_id',
        'product_id',
        'purchase_date',
        'product_amount',
        'product_no',

    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>;
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>;
     */
    protected $casts = [
        'username' => 'string',
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
        'user_type' => UserTypeEnum::class,
        'status' => UserStatusEnum::class,
    ];

    //Hello World......
    public function profile_document()
    {
        return $this->belongsTo(Document::class, 'profile_document_id');
    }

    public function designation()
    {
        return $this->belongsTo(Designation::class);
    }

    public function earnings()
    {
        return $this->hasMany(Earning::class);
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function payments()
    {
        return $this->hasMany(Payment::class);
    }

    public function manager()
    {
        return $this->belongsTo(User::class, 'manager_id');
    }

    public function parent()
    {
        return $this->belongsTo(User::class, 'parent_id');
    }
    public function user_activity()
    {
        return $this->hasOne(UserActivity::class);
    }
    public function joining_benefit(){
        return $this->belongsTo(User::class,'');
    }

    public function children()
    {
        return $this->hasMany(User::class, 'parent_id');
    }
    public function childrenCount()
    {
        return $this->children()->count();
    }

    public function descendants()
    {
        return $this->children()->with('descendants');
    }

    public function hierarchy()
    {
        return $this->children()->with('hierarchy');
    }

    public function limitedDescendants($depth = 1)
    {
        if ($depth <= 0) {
            return $this->children(); // Only direct children
        }

        return $this->children()->with(['limitedDescendants' => function ($query) use ($depth) {
            $query->withDepth($depth - 1);
        }]);
    }

    public function descendantsWithLimit($depth)
    {
        return $this->children()->when($depth > 1, function ($query) use ($depth) {
            $query->with(['descendantsWithLimit' => function ($subQuery) use ($depth) {
                $subQuery->descendantsWithLimit($depth - 1);
            }]);
        });
    }

    protected static function boot()
    {
        parent::boot();
        // Automatically set created_by when creating
        static::creating(function ($model) {
            if (auth()->check()) {
                $model->created_by = auth()->id();
            }
        });

        // Automatically set updated_by when updating
        static::updating(function ($model) {
            if (auth()->check()) {
                $model->updated_by = auth()->id();
            }
        });
        // Listen for the 'creating' event to set default values before a user is created

        static::creating(function ($user) {
            $username = $user->username ?? Str::slug(static::setUnAttribute($user->attributes['name']));
            $user->attributes['username'] = $username;
            $user->attributes['user_type'] = $user->user_type ?? UserTypeEnum::MEMBER;
            $user->attributes['password'] = $user->password ?? Hash::make('password');
            // $user->attributes['manager_id'] = $user->manager_id ?? 2;
            // $user->attributes['parent_id'] = $user->parent_id ?? 2;
            $user->attributes['status'] = $user->status ?? UserStatusEnum::INACTIVE;

        });

    }

    // protected static function setUsernameAttribute($value)
    protected static function setUnAttribute($value)
    {

        // Generate a 10-character username based on the user's name
        //allow only alphabet
        $value = preg_replace('/[^a-zA-Z0-9]+/', '', $value);
        $baseUsername = strtolower(substr(str_replace(' ', '', $value), 0, 6));

        // If the username exists, append a random number to make it unique
        do {
            // Generate a random number
            $randomNumber = str_pad(mt_rand(1, 9999), 4, '0', STR_PAD_LEFT);
            // Append the random number to the base username
            $username = $baseUsername.$randomNumber;
            // Check if the newly generated username exists
            $count = User::where('username', $username)->count();
        } while ($count > 0); // Loop until a unique username is found

        return $username;
    }

    /**
     * Get the identifier that will be stored in the subject claim of the JWT.
     *
     * @return mixed
     */
    public function getJWTIdentifier()
    {
        return $this->getKey();
        //  return $this->email;
    }

    /**
     * Return a key value array, containing any custom claims to be added to the JWT.
     *
     * @return array
     */
    public function getJWTCustomClaims()
    {
        return [];
        // return ['email' => $this->email];
    }
}
