import type { User } from './auth';

export * from './auth';
export * from './navigation';
export * from './ui';

export type Giveaway = {
    slug: string;
    title: string;
    description?: string;
    banner?: string;
    starts_at: string;
    ends_at: string;
    has_started: boolean;
    has_ended: boolean;
    winners_count: number;
    status: GiveawayStatus;
    user: User;
    created_at: string;
    participants_count: number;
    can: {
        update: boolean;
        delete: boolean;
    };
    entry_requirements: FormEntryRequirement[];
};

export type GiveawayStatus = 'scheduled' | 'active' | 'ended' | 'complete';

export type FormEntryRequirement = {
    type: EntryRequirementTypeValue;
    label: string;
    entries: number;
    config?: any;
    slug?: string;
};

export type EntryRequirement = FormEntryRequirement & {
    slug: string;
    has_user_used_this_requirement: boolean;
};

export type EntryRequirementTypeValue =
    | 'button_click'
    | 'secret_code'
    | 'question';

export type Participant = {
    name: string;
    entries: number;
};
