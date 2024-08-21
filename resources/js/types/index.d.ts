export interface User {
    id: number;
    name: string;
    email: string;
    email_verified_at: string;
}

export type PageProps<
    T extends Record<string, unknown> = Record<string, unknown>,
> = T & {
    auth: {
        user: User;
    };
    flash: {
        message: string;
    };
    accounts: {
        id: string;
        Account_Name: string;
        Phone: string;
        Website: string;
    };
    dials: {
        id: string;
        Deal_Name: string;
        Stage: {};
    };
};
