import { createClient } from '@supabase/supabase-js';

const supabaseUrl = import.meta.env.VITE_PUBLIC_SUPABASE_URL;
const supabaseAnonKey = import.meta.env.VITE_PUBLIC_SUPABASE_ANON_KEY;

if (!supabaseUrl || !supabaseAnonKey) {
  throw new Error('Missing Supabase environment variables');
}

export const supabase = createClient(supabaseUrl, supabaseAnonKey);

export interface BuddhistFollower {
  id: string;
  full_name: string;
  dharma_name?: string;
  phone?: string;
  email?: string;
  address?: string;
  birth_date?: string;
  birth_date_lunar?: string;
  gender?: string;
  zodiac_info?: string;
  notes?: string;
  avatar_url?: string;
  created_at: string;
  updated_at: string;
}

export interface DeceasedRelative {
  id: string;
  follower_id: string;
  name: string;
  relationship?: string;
  death_date?: string;
  death_date_lunar?: string;
  notes?: string;
  created_at: string;
}

export interface Event {
  id: string;
  title: string;
  description?: string;
  event_type?: string;
  event_date: string;
  event_date_lunar?: string;
  location?: string;
  organizer?: string;
  status: string;
  notify_followers: boolean;
  created_at: string;
  updated_at: string;
}

export interface MeritActivity {
  id: string;
  follower_id: string;
  activity_name: string;
  activity_type?: string;
  activity_date: string;
  hours?: number;
  description?: string;
  created_at: string;
}

export interface Finance {
  id: string;
  follower_id?: string;
  transaction_type: string;
  category?: string;
  amount: number;
  transaction_date: string;
  description?: string;
  payment_method?: string;
  receipt_url?: string;
  created_at: string;
}

export interface Asset {
  id: string;
  asset_name: string;
  asset_type?: string;
  category?: string;
  purchase_date?: string;
  purchase_value?: number;
  current_value?: number;
  location?: string;
  condition?: string;
  description?: string;
  image_url?: string;
  created_at: string;
  updated_at: string;
}

export interface Notification {
  id: string;
  title: string;
  message: string;
  notification_type?: string;
  target_audience: string;
  scheduled_date?: string;
  sent_date?: string;
  status: string;
  created_at: string;
}
