<?php

namespace App\Console\Commands;

use App\Models\Role;
use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

/**
 * Command for creating a new user with admin permissions
 * via the console interface.
 */
class CreateModeratorUser extends Command
{

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'user:create-mod';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Register a new user account with moderation permissions';

    /**
     * List of credentials that will be asked to the user.
     *
     * @var array
     */
    protected $credentials = [
        [
            'key'        => 'name',
            'question'   => 'Select a username',
            'validation' => 'required|unique:users|min:4',
        ],
        [
            'key'        => 'email',
            'question'   => 'Select an email address',
            'validation' => 'required|email',
        ],
        [
            'key'        => 'password',
            'question'   => 'Select a password',
            'validation' => 'required|min:6',
        ],
    ];


    /**
     * Execute the console command.
     *
     * @return integer
     */
    public function handle(): int
    {
        $data = [];

        foreach ($this->credentials as $credential) {
            $fieldIsValid = FALSE;

            while (! $fieldIsValid) {
                $field = $credential['key'] === 'password'
                    ? $this->secret($credential['question'])
                    : $this->ask($credential['question']);

                $validator = Validator::make(
                    [$credential['key'] => $field],
                    [$credential['key'] => $credential['validation']]
                );

                if ($validator->fails()) {
                    foreach ($validator->errors()->all() as $error) {
                        $this->error($error);
                    }
                } else {
                    $fieldIsValid             = TRUE;
                    $data[$credential['key']] = $field;
                }
            }
        }//end foreach

        $data['password'] = Hash::make($data['password']);
        $data['role_id']  = Role::ADMIN;

        User::create($data);

        $this->info('User created successfully!');

        return Command::SUCCESS;

    }//end handle()


}//end class
