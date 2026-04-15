export type User = {
    name: string;
    email: string;
    email_verified_at: string | null;
    created_at: string;
    updated_at: string;
    [key: string]: unknown;
    role: string;
};

export type Auth = {
    user: User;
    can_create_giveaway: boolean;
    is_staff: boolean;
};

export type TwoFactorConfigContent = {
    title: string;
    description: string;
    buttonText: string;
};
