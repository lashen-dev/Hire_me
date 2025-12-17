<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class RolePermissionSeeder extends Seeder
{
    use WithoutModelEvents; // Use the trait to prevent model events

    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Wrap operations in a transaction
        DB::transaction(function () {
            // Define all permissions (avoid duplicates)
            $permissions = [
                // Admin permissions
                'manage-users',
                'view-users',
                'delete-companies',
                'delete-applicants',
                'view-analytics',
                'manage-companies',
                'view-companies',
                'view-applicants',
                // Company permissions
                'post-job',
                'delete-jobs',
                'view-applicants-company',
                'manage-applicants-apply',
                'answer-application',
                'complete-company-profile',
                // Applicant permissions
                'apply-job', // Consolidated 'can-apply-job' and 'apply-job'
                'cancel-application',
                'complete-applicant-profile',
                // Shared permissions
                'view-jobs',
                'view-company-profile',
                'view-applicant-profile',
                'view-applications',
                'applicants-company',
                'view-applicantions', 
                
                
            ];

            // Create permissions if they don't exist
            foreach ($permissions as $permission) {
                Permission::firstOrCreate(['name' => $permission]);
            }

            // Create or update roles and assign permissions
            $adminRole = Role::firstOrCreate(['name' => 'admin']);
            $adminRole->syncPermissions([
                'manage-users',
                'view-users',
                'delete-companies',
                'delete-applicants',
                'delete-jobs',
                'view-analytics',
                'manage-companies',
                'view-companies',
                'view-applicants',
                'view-jobs',
                'view-applicants-company',
                'manage-applicants-apply',
                'answer-application',
                'view-company-profile',
                'view-applicant-profile',
                'view-applications',
                


            ]);

            $companyRole = Role::firstOrCreate(['name' => 'company']);
            $companyRole->syncPermissions([
                'post-job',
                'delete-jobs',
                'view-jobs',
                'view-applicants-company',
                'manage-applicants-apply',
                'answer-application',
                'view-company-profile',
                'view-applicant-profile',
                'view-companies',
                'apply-job',
                'cancel-application',
                'view-applications',
                'manage-companies',
                'applicants-company',
                'complete-company-profile',
                
            ]);

            $applicantRole = Role::firstOrCreate(['name' => 'applicant']);
            $applicantRole->syncPermissions([
                'view-jobs',
                'view-companies',
                'apply-job',
                'cancel-application',
                'view-applicantions',
                'view-applicant-profile',
                'view-company-profile',
                'complete-applicant-profile',
            ]);

            
        });
    }
}