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
    winners_count: number;
    status: GiveawayStatus;
    user: User;
    created_at: string;
    participants_count: number;
};

export type GiveawayStatus = 'scheduled' | 'active' | 'ended' | 'complete';
