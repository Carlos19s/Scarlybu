--
-- PostgreSQL database dump
--

\restrict d5gl9T7N6Ys1YN8W8TRVXt9NeVQ4IaaKKCHFB7bLShfmqhU1L5AUkOWEIYgs47x

-- Dumped from database version 17.6
-- Dumped by pg_dump version 18.3

SET statement_timeout = 0;
SET lock_timeout = 0;
SET idle_in_transaction_session_timeout = 0;
SET transaction_timeout = 0;
SET client_encoding = 'UTF8';
SET standard_conforming_strings = on;
SELECT pg_catalog.set_config('search_path', '', false);
SET check_function_bodies = false;
SET xmloption = content;
SET client_min_messages = warning;
SET row_security = off;

--
-- Data for Name: audit_log_entries; Type: TABLE DATA; Schema: auth; Owner: supabase_auth_admin
--



--
-- Data for Name: custom_oauth_providers; Type: TABLE DATA; Schema: auth; Owner: supabase_auth_admin
--



--
-- Data for Name: flow_state; Type: TABLE DATA; Schema: auth; Owner: supabase_auth_admin
--



--
-- Data for Name: users; Type: TABLE DATA; Schema: auth; Owner: supabase_auth_admin
--



--
-- Data for Name: identities; Type: TABLE DATA; Schema: auth; Owner: supabase_auth_admin
--



--
-- Data for Name: instances; Type: TABLE DATA; Schema: auth; Owner: supabase_auth_admin
--



--
-- Data for Name: oauth_clients; Type: TABLE DATA; Schema: auth; Owner: supabase_auth_admin
--



--
-- Data for Name: sessions; Type: TABLE DATA; Schema: auth; Owner: supabase_auth_admin
--



--
-- Data for Name: mfa_amr_claims; Type: TABLE DATA; Schema: auth; Owner: supabase_auth_admin
--



--
-- Data for Name: mfa_factors; Type: TABLE DATA; Schema: auth; Owner: supabase_auth_admin
--



--
-- Data for Name: mfa_challenges; Type: TABLE DATA; Schema: auth; Owner: supabase_auth_admin
--



--
-- Data for Name: oauth_authorizations; Type: TABLE DATA; Schema: auth; Owner: supabase_auth_admin
--



--
-- Data for Name: oauth_client_states; Type: TABLE DATA; Schema: auth; Owner: supabase_auth_admin
--



--
-- Data for Name: oauth_consents; Type: TABLE DATA; Schema: auth; Owner: supabase_auth_admin
--



--
-- Data for Name: one_time_tokens; Type: TABLE DATA; Schema: auth; Owner: supabase_auth_admin
--



--
-- Data for Name: refresh_tokens; Type: TABLE DATA; Schema: auth; Owner: supabase_auth_admin
--



--
-- Data for Name: sso_providers; Type: TABLE DATA; Schema: auth; Owner: supabase_auth_admin
--



--
-- Data for Name: saml_providers; Type: TABLE DATA; Schema: auth; Owner: supabase_auth_admin
--



--
-- Data for Name: saml_relay_states; Type: TABLE DATA; Schema: auth; Owner: supabase_auth_admin
--



--
-- Data for Name: schema_migrations; Type: TABLE DATA; Schema: auth; Owner: supabase_auth_admin
--

INSERT INTO auth.schema_migrations (version) VALUES ('20171026211738');
INSERT INTO auth.schema_migrations (version) VALUES ('20171026211808');
INSERT INTO auth.schema_migrations (version) VALUES ('20171026211834');
INSERT INTO auth.schema_migrations (version) VALUES ('20180103212743');
INSERT INTO auth.schema_migrations (version) VALUES ('20180108183307');
INSERT INTO auth.schema_migrations (version) VALUES ('20180119214651');
INSERT INTO auth.schema_migrations (version) VALUES ('20180125194653');
INSERT INTO auth.schema_migrations (version) VALUES ('00');
INSERT INTO auth.schema_migrations (version) VALUES ('20210710035447');
INSERT INTO auth.schema_migrations (version) VALUES ('20210722035447');
INSERT INTO auth.schema_migrations (version) VALUES ('20210730183235');
INSERT INTO auth.schema_migrations (version) VALUES ('20210909172000');
INSERT INTO auth.schema_migrations (version) VALUES ('20210927181326');
INSERT INTO auth.schema_migrations (version) VALUES ('20211122151130');
INSERT INTO auth.schema_migrations (version) VALUES ('20211124214934');
INSERT INTO auth.schema_migrations (version) VALUES ('20211202183645');
INSERT INTO auth.schema_migrations (version) VALUES ('20220114185221');
INSERT INTO auth.schema_migrations (version) VALUES ('20220114185340');
INSERT INTO auth.schema_migrations (version) VALUES ('20220224000811');
INSERT INTO auth.schema_migrations (version) VALUES ('20220323170000');
INSERT INTO auth.schema_migrations (version) VALUES ('20220429102000');
INSERT INTO auth.schema_migrations (version) VALUES ('20220531120530');
INSERT INTO auth.schema_migrations (version) VALUES ('20220614074223');
INSERT INTO auth.schema_migrations (version) VALUES ('20220811173540');
INSERT INTO auth.schema_migrations (version) VALUES ('20221003041349');
INSERT INTO auth.schema_migrations (version) VALUES ('20221003041400');
INSERT INTO auth.schema_migrations (version) VALUES ('20221011041400');
INSERT INTO auth.schema_migrations (version) VALUES ('20221020193600');
INSERT INTO auth.schema_migrations (version) VALUES ('20221021073300');
INSERT INTO auth.schema_migrations (version) VALUES ('20221021082433');
INSERT INTO auth.schema_migrations (version) VALUES ('20221027105023');
INSERT INTO auth.schema_migrations (version) VALUES ('20221114143122');
INSERT INTO auth.schema_migrations (version) VALUES ('20221114143410');
INSERT INTO auth.schema_migrations (version) VALUES ('20221125140132');
INSERT INTO auth.schema_migrations (version) VALUES ('20221208132122');
INSERT INTO auth.schema_migrations (version) VALUES ('20221215195500');
INSERT INTO auth.schema_migrations (version) VALUES ('20221215195800');
INSERT INTO auth.schema_migrations (version) VALUES ('20221215195900');
INSERT INTO auth.schema_migrations (version) VALUES ('20230116124310');
INSERT INTO auth.schema_migrations (version) VALUES ('20230116124412');
INSERT INTO auth.schema_migrations (version) VALUES ('20230131181311');
INSERT INTO auth.schema_migrations (version) VALUES ('20230322519590');
INSERT INTO auth.schema_migrations (version) VALUES ('20230402418590');
INSERT INTO auth.schema_migrations (version) VALUES ('20230411005111');
INSERT INTO auth.schema_migrations (version) VALUES ('20230508135423');
INSERT INTO auth.schema_migrations (version) VALUES ('20230523124323');
INSERT INTO auth.schema_migrations (version) VALUES ('20230818113222');
INSERT INTO auth.schema_migrations (version) VALUES ('20230914180801');
INSERT INTO auth.schema_migrations (version) VALUES ('20231027141322');
INSERT INTO auth.schema_migrations (version) VALUES ('20231114161723');
INSERT INTO auth.schema_migrations (version) VALUES ('20231117164230');
INSERT INTO auth.schema_migrations (version) VALUES ('20240115144230');
INSERT INTO auth.schema_migrations (version) VALUES ('20240214120130');
INSERT INTO auth.schema_migrations (version) VALUES ('20240306115329');
INSERT INTO auth.schema_migrations (version) VALUES ('20240314092811');
INSERT INTO auth.schema_migrations (version) VALUES ('20240427152123');
INSERT INTO auth.schema_migrations (version) VALUES ('20240612123726');
INSERT INTO auth.schema_migrations (version) VALUES ('20240729123726');
INSERT INTO auth.schema_migrations (version) VALUES ('20240802193726');
INSERT INTO auth.schema_migrations (version) VALUES ('20240806073726');
INSERT INTO auth.schema_migrations (version) VALUES ('20241009103726');
INSERT INTO auth.schema_migrations (version) VALUES ('20250717082212');
INSERT INTO auth.schema_migrations (version) VALUES ('20250731150234');
INSERT INTO auth.schema_migrations (version) VALUES ('20250804100000');
INSERT INTO auth.schema_migrations (version) VALUES ('20250901200500');
INSERT INTO auth.schema_migrations (version) VALUES ('20250903112500');
INSERT INTO auth.schema_migrations (version) VALUES ('20250904133000');
INSERT INTO auth.schema_migrations (version) VALUES ('20250925093508');
INSERT INTO auth.schema_migrations (version) VALUES ('20251007112900');
INSERT INTO auth.schema_migrations (version) VALUES ('20251104100000');
INSERT INTO auth.schema_migrations (version) VALUES ('20251111201300');
INSERT INTO auth.schema_migrations (version) VALUES ('20251201000000');
INSERT INTO auth.schema_migrations (version) VALUES ('20260115000000');
INSERT INTO auth.schema_migrations (version) VALUES ('20260121000000');
INSERT INTO auth.schema_migrations (version) VALUES ('20260219120000');
INSERT INTO auth.schema_migrations (version) VALUES ('20260302000000');


--
-- Data for Name: sso_domains; Type: TABLE DATA; Schema: auth; Owner: supabase_auth_admin
--



--
-- Data for Name: webauthn_challenges; Type: TABLE DATA; Schema: auth; Owner: supabase_auth_admin
--



--
-- Data for Name: webauthn_credentials; Type: TABLE DATA; Schema: auth; Owner: supabase_auth_admin
--



--
-- Data for Name: cache; Type: TABLE DATA; Schema: public; Owner: postgres
--

INSERT INTO public.cache (key, value, expiration) VALUES ('laravel-cache-category_3_all_ids', 'a:3:{i:0;i:3;i:1;i:1;i:2;i:2;}', 1781190977);
INSERT INTO public.cache (key, value, expiration) VALUES ('laravel-cache-category_4_all_ids', 'a:1:{i:0;i:4;}', 1781190981);
INSERT INTO public.cache (key, value, expiration) VALUES ('laravel-cache-category_5_all_ids', 'a:1:{i:0;i:5;}', 1781190987);
INSERT INTO public.cache (key, value, expiration) VALUES ('laravel-cache-category_6_all_ids', 'a:1:{i:0;i:6;}', 1781190992);
INSERT INTO public.cache (key, value, expiration) VALUES ('laravel-cache-category_7_all_ids', 'a:1:{i:0;i:7;}', 1781190997);
INSERT INTO public.cache (key, value, expiration) VALUES ('laravel-cache-b32c77175a4b1e3fea3ff4c3adbefacb:timer', 'i:1781187468;', 1781187469);
INSERT INTO public.cache (key, value, expiration) VALUES ('laravel-cache-b32c77175a4b1e3fea3ff4c3adbefacb', 'i:1;', 1781187470);
INSERT INTO public.cache (key, value, expiration) VALUES ('laravel-cache-spatie.permission.cache', 'a:3:{s:5:"alias";a:4:{s:1:"a";s:2:"id";s:1:"b";s:4:"name";s:1:"c";s:10:"guard_name";s:1:"r";s:5:"roles";}s:11:"permissions";a:5:{i:0;a:4:{s:1:"a";i:1;s:1:"b";s:14:"manage_catalog";s:1:"c";s:3:"web";s:1:"r";a:1:{i:0;i:1;}}i:1;a:4:{s:1:"a";i:2;s:1:"b";s:16:"manage_inventory";s:1:"c";s:3:"web";s:1:"r";a:1:{i:0;i:1;}}i:2;a:4:{s:1:"a";i:3;s:1:"b";s:13:"manage_orders";s:1:"c";s:3:"web";s:1:"r";a:1:{i:0;i:2;}}i:3;a:4:{s:1:"a";i:4;s:1:"b";s:14:"view_customers";s:1:"c";s:3:"web";s:1:"r";a:1:{i:0;i:2;}}i:4;a:4:{s:1:"a";i:5;s:1:"b";s:12:"manage_users";s:1:"c";s:3:"web";s:1:"r";a:1:{i:0;i:4;}}}s:5:"roles";a:3:{i:0;a:3:{s:1:"a";i:1;s:1:"b";s:7:"gerente";s:1:"c";s:3:"web";}i:1;a:3:{s:1:"a";i:2;s:1:"b";s:8:"vendedor";s:1:"c";s:3:"web";}i:2;a:3:{s:1:"a";i:4;s:1:"b";s:13:"admin_sistema";s:1:"c";s:3:"web";}}}', 1781273831);
INSERT INTO public.cache (key, value, expiration) VALUES ('laravel-cache-dc44958e29ffba8b810d21377ae366b5:timer', 'i:1781188254;', 1781188255);
INSERT INTO public.cache (key, value, expiration) VALUES ('laravel-cache-dc44958e29ffba8b810d21377ae366b5', 'i:1;', 1781188257);
INSERT INTO public.cache (key, value, expiration) VALUES ('laravel-cache-f6bdf95d80d818c188d9722b4010e857:timer', 'i:1781189126;', 1781189127);
INSERT INTO public.cache (key, value, expiration) VALUES ('laravel-cache-f6bdf95d80d818c188d9722b4010e857', 'i:1;', 1781189130);
INSERT INTO public.cache (key, value, expiration) VALUES ('laravel-cache-category_3_product_count', 'i:1;', 1781191121);
INSERT INTO public.cache (key, value, expiration) VALUES ('laravel-cache-category_4_product_count', 'i:0;', 1781191127);
INSERT INTO public.cache (key, value, expiration) VALUES ('laravel-cache-category_5_product_count', 'i:1;', 1781191131);
INSERT INTO public.cache (key, value, expiration) VALUES ('laravel-cache-category_6_product_count', 'i:0;', 1781191137);
INSERT INTO public.cache (key, value, expiration) VALUES ('laravel-cache-category_7_product_count', 'i:0;', 1781191142);


--
-- Data for Name: cache_locks; Type: TABLE DATA; Schema: public; Owner: postgres
--



--
-- Data for Name: categories; Type: TABLE DATA; Schema: public; Owner: postgres
--

INSERT INTO public.categories (id, nombre, slug, descripcion, parent_id, imagen, activa, created_at, updated_at) VALUES (3, 'Gorras', 'gorras', 'Gorras de todos los estilos y marcas', NULL, NULL, true, '2026-06-10 07:44:42', '2026-06-10 07:44:42');
INSERT INTO public.categories (id, nombre, slug, descripcion, parent_id, imagen, activa, created_at, updated_at) VALUES (4, 'Accesorios', 'accesorios', 'Accesorios de moda para cualquier ocasión', NULL, NULL, true, '2026-06-10 07:44:44', '2026-06-10 07:44:44');
INSERT INTO public.categories (id, nombre, slug, descripcion, parent_id, imagen, activa, created_at, updated_at) VALUES (5, 'Cosméticos', 'cosmeticos', 'Productos de belleza y cuidado personal', NULL, NULL, true, '2026-06-10 07:44:45', '2026-06-10 07:44:45');
INSERT INTO public.categories (id, nombre, slug, descripcion, parent_id, imagen, activa, created_at, updated_at) VALUES (6, 'Ropa', 'ropa', 'Ropa casual y de tendencia', NULL, NULL, true, '2026-06-10 07:44:46', '2026-06-10 07:44:46');
INSERT INTO public.categories (id, nombre, slug, descripcion, parent_id, imagen, activa, created_at, updated_at) VALUES (7, 'Zapatos', 'zapatos', 'Calzado para todos los gustos', NULL, NULL, true, '2026-06-10 07:44:47', '2026-06-10 07:44:47');
INSERT INTO public.categories (id, nombre, slug, descripcion, parent_id, imagen, activa, created_at, updated_at) VALUES (1, 'Gorra con Malla', 'gorra-con-malla', 'Gorras con malla', 3, NULL, true, '2026-06-10 07:04:16', '2026-06-10 08:52:59');
INSERT INTO public.categories (id, nombre, slug, descripcion, parent_id, imagen, activa, created_at, updated_at) VALUES (2, 'Gorras Rojas', 'gorras-rojas', '', 3, NULL, true, '2026-06-10 07:04:56', '2026-06-10 08:52:59');


--
-- Data for Name: failed_jobs; Type: TABLE DATA; Schema: public; Owner: postgres
--



--
-- Data for Name: job_batches; Type: TABLE DATA; Schema: public; Owner: postgres
--



--
-- Data for Name: jobs; Type: TABLE DATA; Schema: public; Owner: postgres
--



--
-- Data for Name: migrations; Type: TABLE DATA; Schema: public; Owner: postgres
--

INSERT INTO public.migrations (id, migration, batch) VALUES (1, '0001_01_01_000000_create_users_table', 1);
INSERT INTO public.migrations (id, migration, batch) VALUES (2, '0001_01_01_000001_create_cache_table', 1);
INSERT INTO public.migrations (id, migration, batch) VALUES (3, '0001_01_01_000002_create_jobs_table', 1);
INSERT INTO public.migrations (id, migration, batch) VALUES (4, '2024_01_01_000000_create_passkeys_table', 1);
INSERT INTO public.migrations (id, migration, batch) VALUES (5, '2025_08_14_170933_add_two_factor_columns_to_users_table', 1);
INSERT INTO public.migrations (id, migration, batch) VALUES (6, '2026_06_04_025312_add_role_to_users_table', 1);
INSERT INTO public.migrations (id, migration, batch) VALUES (7, '2026_06_04_025320_create_categories_table', 1);
INSERT INTO public.migrations (id, migration, batch) VALUES (8, '2026_06_04_025322_create_products_table', 1);
INSERT INTO public.migrations (id, migration, batch) VALUES (9, '2026_06_04_025326_create_orders_table', 1);
INSERT INTO public.migrations (id, migration, batch) VALUES (10, '2026_06_04_025331_create_order_items_table', 1);
INSERT INTO public.migrations (id, migration, batch) VALUES (11, '2026_06_04_025335_create_settings_table', 1);
INSERT INTO public.migrations (id, migration, batch) VALUES (12, '2026_06_10_082546_add_cliente_documento_to_orders_table', 2);
INSERT INTO public.migrations (id, migration, batch) VALUES (13, '2026_06_11_021217_create_permission_tables', 3);


--
-- Data for Name: permissions; Type: TABLE DATA; Schema: public; Owner: postgres
--

INSERT INTO public.permissions (id, name, guard_name, created_at, updated_at) VALUES (1, 'manage_catalog', 'web', '2026-06-11 02:14:22', '2026-06-11 02:14:22');
INSERT INTO public.permissions (id, name, guard_name, created_at, updated_at) VALUES (2, 'manage_inventory', 'web', '2026-06-11 02:14:23', '2026-06-11 02:14:23');
INSERT INTO public.permissions (id, name, guard_name, created_at, updated_at) VALUES (3, 'manage_orders', 'web', '2026-06-11 02:14:24', '2026-06-11 02:14:24');
INSERT INTO public.permissions (id, name, guard_name, created_at, updated_at) VALUES (4, 'view_customers', 'web', '2026-06-11 02:14:25', '2026-06-11 02:14:25');
INSERT INTO public.permissions (id, name, guard_name, created_at, updated_at) VALUES (5, 'manage_users', 'web', '2026-06-11 02:14:26', '2026-06-11 02:14:26');


--
-- Data for Name: model_has_permissions; Type: TABLE DATA; Schema: public; Owner: postgres
--



--
-- Data for Name: roles; Type: TABLE DATA; Schema: public; Owner: postgres
--

INSERT INTO public.roles (id, name, guard_name, created_at, updated_at) VALUES (1, 'gerente', 'web', '2026-06-11 02:14:27', '2026-06-11 02:14:27');
INSERT INTO public.roles (id, name, guard_name, created_at, updated_at) VALUES (2, 'vendedor', 'web', '2026-06-11 02:14:30', '2026-06-11 02:14:30');
INSERT INTO public.roles (id, name, guard_name, created_at, updated_at) VALUES (3, 'cliente', 'web', '2026-06-11 02:14:33', '2026-06-11 02:14:33');
INSERT INTO public.roles (id, name, guard_name, created_at, updated_at) VALUES (4, 'admin_sistema', 'web', '2026-06-11 02:14:34', '2026-06-11 02:14:34');


--
-- Data for Name: model_has_roles; Type: TABLE DATA; Schema: public; Owner: postgres
--

INSERT INTO public.model_has_roles (role_id, model_type, model_id) VALUES (4, 'App\Models\User', 4);
INSERT INTO public.model_has_roles (role_id, model_type, model_id) VALUES (1, 'App\Models\User', 5);
INSERT INTO public.model_has_roles (role_id, model_type, model_id) VALUES (2, 'App\Models\User', 6);
INSERT INTO public.model_has_roles (role_id, model_type, model_id) VALUES (3, 'App\Models\User', 7);
INSERT INTO public.model_has_roles (role_id, model_type, model_id) VALUES (3, 'App\Models\User', 2);


--
-- Data for Name: users; Type: TABLE DATA; Schema: public; Owner: postgres
--

INSERT INTO public.users (id, name, email, email_verified_at, password, remember_token, created_at, updated_at, two_factor_secret, two_factor_recovery_codes, two_factor_confirmed_at, role) VALUES (4, 'Admin User', 'admin@example.com', NULL, '$2y$12$YPqIOFxOAn/R3UDHjTkedunZtYhW2W4e41UTGhs8QLhfwzPzabpuW', NULL, '2026-06-11 02:14:39', '2026-06-11 02:14:39', NULL, NULL, NULL, 'vendedor');
INSERT INTO public.users (id, name, email, email_verified_at, password, remember_token, created_at, updated_at, two_factor_secret, two_factor_recovery_codes, two_factor_confirmed_at, role) VALUES (5, 'Gerente User', 'gerente@example.com', NULL, '$2y$12$aOpKa8Bw2KlaP9vgj9oelOWafjOupXd8sp2Qja3RH1ggaAYkr1Oea', NULL, '2026-06-11 02:14:41', '2026-06-11 02:14:41', NULL, NULL, NULL, 'vendedor');
INSERT INTO public.users (id, name, email, email_verified_at, password, remember_token, created_at, updated_at, two_factor_secret, two_factor_recovery_codes, two_factor_confirmed_at, role) VALUES (6, 'Vendedor User', 'vendedor@example.com', NULL, '$2y$12$4ePRsg78Gvg2/g3u6.gejeOrccY1BocH3eKPqle60kUcGHsQa8nJ2', NULL, '2026-06-11 02:14:44', '2026-06-11 02:14:44', NULL, NULL, NULL, 'vendedor');
INSERT INTO public.users (id, name, email, email_verified_at, password, remember_token, created_at, updated_at, two_factor_secret, two_factor_recovery_codes, two_factor_confirmed_at, role) VALUES (2, 'Carlos Moncayo', 'stevematson9@gmail.com', NULL, '$2y$12$IMacpPQZUe5BvBLQxoN/iuhH6IbmAmfbTWxOI4I8DFJQWp3wtHbpK', NULL, '2026-06-10 08:38:05', '2026-06-10 08:44:33', 'eyJpdiI6IkJqT1RiL0loK0lMOGU3WldFOFVHTlE9PSIsInZhbHVlIjoidnExWmVMUXlOaUg0YlhtK0JDTUZxVWZSMTZwa0VJMC9BVWE3bkgyREc4MD0iLCJtYWMiOiJkZTU2NzExOGUxZDIzN2ZiM2Q5MDY5ZWZmZWU4ZjllMjgwZjc4N2U3MWNjYjhjNGY1YWM3NDg5YTlkZTNiMTJlIiwidGFnIjoiIn0=', 'eyJpdiI6IkN1YnFpSHlYSjQvdFU1YXlHbkNqeWc9PSIsInZhbHVlIjoiVjNuVS93dDEyQ08zRnc2UGR2Tm91dlV5a0dtT2VEWThtSzBGS3M1T1BVOWpwbzNBOENCb3U3T2pjTk80cFNIZXBJSzN0YjBZUDEwNlV4YWptc3QvaWJvYnJmWWtoMXFYem5tZWdFMHJidldlVW5qVEp4a0ZYL1NHSzM4VE1VZXJxU3BSR3pTNmxYUzgxbTRUWUVPQUZSQWgvTlFoQUlqY3JJRmVaajV0TUgybFdueEIwNWF4MnZ4L3c1U0J5OUN3aWtrQzBLRkhvYWI0bzVhQVBHbHZuT1FEenJuLzFjazRWMFFZaFZYWEdJNkkyVHVYUmZXQ1VGMDJwT2Y5OFppMjBmK3N3bWNJdmkzRjVmNGVLRTdDRVE9PSIsIm1hYyI6IjI5NmM0MjVlYzk2ZTNmZGQyNWUyNGQwZmYyOWFlZjZmZjkxNDQ0Y2UwMTQ1Mjc3YjBjYmE4MWY5M2QyYzI4ZDkiLCJ0YWciOiIifQ==', NULL, 'vendedor');
INSERT INTO public.users (id, name, email, email_verified_at, password, remember_token, created_at, updated_at, two_factor_secret, two_factor_recovery_codes, two_factor_confirmed_at, role) VALUES (7, 'Cliente User', 'cliente@example.com', NULL, '$2y$12$ajVFr.2a9CfyADux.G3NceZH74bD7inIU.x9NovaVGCrXfwR2uoyK', NULL, '2026-06-11 02:14:46', '2026-06-11 02:14:46', NULL, NULL, NULL, 'vendedor');
INSERT INTO public.users (id, name, email, email_verified_at, password, remember_token, created_at, updated_at, two_factor_secret, two_factor_recovery_codes, two_factor_confirmed_at, role) VALUES (1, 'Admin', 'admin@scarlybu.com', '2026-06-10 06:45:35', '$2y$12$4elGnBSQbm2tvfy1p3rC/./Q.tq0sNOGX9IEH8y84pGHjp7W54FdK', '63nUdGLjiT5uGVmdcPPG90O961V4lKymh7pHcGNNEBkZnjnhKq4e7Vp2sJBj', '2026-06-10 06:45:35', '2026-06-10 16:21:16', 'eyJpdiI6Ikc5elBZTkRjODJ4VVBiMUtvWXUzUXc9PSIsInZhbHVlIjoiMXkvY21xUmprNW1kWWN5UzRpa2p1N2l5VVBLOE1tanBqdm9ib3VLTFhZZz0iLCJtYWMiOiJkMDQ3OGI0NmZmZjJlOWU3NzZjYzY2NmE5NjA5NTUyODliY2E2ZjEwN2Q0ODYxNzdjNzY4ZTFiOGJmZDQwYmM5IiwidGFnIjoiIn0=', 'eyJpdiI6IlhJTVQ3dVAraGlhdzV5ZnN0WWp2MGc9PSIsInZhbHVlIjoiSUpELzZjdm81Y2VXN2J5VDR5OGNuWjdDU1Q3OE1vbEE3b3hESFZOUTZWQWNxQVVJTEVWMUp0Q3lVT05uN1V4TkRIb3BWVWtPUDljQVZXMVJTdEUwM3NYVGdram9iWXpObEM0Q29xYXl6WWhDL0FHWmQzUUJyczVNbXh5N3RkcmZ6bHdYZnc1bWVUM0c3SDhuc0I0SlVKcSsya3J3dzBYdEd6THpOaWFjYzJUUjRFamZoTTA4cVR3MDA4dDl0T3lQa3duRXdDMWVoSklhQmxIQ0lkaFBoSnZnTmRqV1pBUHQ5RFNnQWtXc2dRZlZLelRuR0NSMHpVN2VyTE05dzh1ZndrVTN2K2tDYXhIM0lHWC9kUEg4SGc9PSIsIm1hYyI6Ijc1NjkxNjkwZDVjYThkYzY1N2YzZWExMTFkNTU2YjZkOWI1NDI0ZWUxMzI0OWUyMzIyMDE0NTY2YjlmYmJkOTQiLCJ0YWciOiIifQ==', NULL, 'admin');
INSERT INTO public.users (id, name, email, email_verified_at, password, remember_token, created_at, updated_at, two_factor_secret, two_factor_recovery_codes, two_factor_confirmed_at, role) VALUES (8, 'Guerrero Quintana Carlos Alberto', 'carlosg72quintana@gmail.com', NULL, '$2y$12$YBYKAbZYdsVG/NaeENAFXe2stKfQRhOIu3vSnhdAEPutQ2O2ywouq', NULL, '2026-06-11 14:40:04', '2026-06-11 14:40:04', NULL, NULL, NULL, 'vendedor');
INSERT INTO public.users (id, name, email, email_verified_at, password, remember_token, created_at, updated_at, two_factor_secret, two_factor_recovery_codes, two_factor_confirmed_at, role) VALUES (3, 'Carlos Stalin Moncayo Velastegui', 'carlosm110206@gmail.com', NULL, '$2y$12$tQsLcp3hhRKaDbzVwYqRB.gGpo0OWWD4rhiardtS8YEIwsiABjw12', NULL, '2026-06-10 16:24:21', '2026-06-10 16:47:14', 'eyJpdiI6IkM4cUIwMk5CbFpTY2FjYjRiRzFvL1E9PSIsInZhbHVlIjoiQTd4LzFTVGJ4R21JOUlQbndsamIybG1xVnZTcnJDZ3dlYlRVT3lpNFJlWT0iLCJtYWMiOiI0ODA0NjJhZGEyZmNiN2E4M2U0Y2UwOTQ2MjYwYmUzYTgzODNjOGE4MWQ5MDQ3N2FjNjBlN2M4MDEyM2FiYWY5IiwidGFnIjoiIn0=', 'eyJpdiI6IisyT014eDhvdmpWL3dmUWNJWEFxV2c9PSIsInZhbHVlIjoiKzByZThBdTl4bEE3TzJwdHlsZkU4dnpzT2t3RnpXZE1ZeVlML01NNkYxKzhEMjJBQW1YdkxRWUZwQldxYklHNXZrY21vaDhFcGRuY2l2UitUV2l4OHh3bHNEcjNwQmZsR3BYcmNmSUdYTFFnOFM0WnByV2RBVm1KenVwRkpKRmd5am1jMWtPUVpIVWswYWdUQWx0RUFxV2REd0RtMVQ1WmlKNWQxVjNQaEZ2OW9zcURyWjF4SldmZG5uM2RFUE9NK1pxVFFrVGlHYnNuUXN1TE01YXJNNTZHU0ZHS0YrR1Rnd21GRkRzWUY5UGFTaXJnUjl0MEhzZ0JkamtLYVZ2bnBCLzdXSWxFRkdtRitmNDVJci9hNnc9PSIsIm1hYyI6IjRiMzU5YjVmYTZmODE5Y2E5NDdkMGRhMDlmOGIyZGRjNDIxYjA0YjhkNjlhNjhhMjM0M2FhODNiYTI2M2Y4YTciLCJ0YWciOiIifQ==', '2026-06-10 16:47:14', 'vendedor');


--
-- Data for Name: orders; Type: TABLE DATA; Schema: public; Owner: postgres
--

INSERT INTO public.orders (id, user_id, numero_pedido, cliente_nombre, cliente_telefono, cliente_correo, cliente_direccion, estado, total, total_iva, es_vip, notas, created_at, updated_at, cliente_documento) VALUES (1, 1, 'SC-EEU8HOUS', 'Carlos Moncayo', '0980951601', 'carlosm110206@gmail.com', 'Ulpiano Perez Quinones 5-59 y José Hidalgo', 'no_revisado', 15.00, 0.00, false, 'En la puerta blanca alado del porton blanco', '2026-06-10 07:48:40', '2026-06-10 07:48:40', NULL);
INSERT INTO public.orders (id, user_id, numero_pedido, cliente_nombre, cliente_telefono, cliente_correo, cliente_direccion, estado, total, total_iva, es_vip, notas, created_at, updated_at, cliente_documento) VALUES (2, 1, 'SC-LNEFTIYZ', 'Carlos Moncayo', '0980951601', 'carlosm110206@gmail.com', 'Ulpiano Perez Quinones 5-59 y José Hidalgo', 'en_proceso', 15.00, 0.00, false, 'En la puerta blanca alado del porton blanco', '2026-06-10 07:51:00', '2026-06-10 07:53:50', NULL);
INSERT INTO public.orders (id, user_id, numero_pedido, cliente_nombre, cliente_telefono, cliente_correo, cliente_direccion, estado, total, total_iva, es_vip, notas, created_at, updated_at, cliente_documento) VALUES (3, 2, 'SC-6A292331BD5E5', 'Carlos Stalin Moncayo Velastegui', '0980951601', 'stevematson9@gmail.com', 'Ulpiano Perez Quinones 5-59 Y José Hidalgo', 'no_revisado', 45.00, 0.00, false, 'Puerta blanca', '2026-06-10 08:41:21', '2026-06-10 08:41:21', '1050343019');
INSERT INTO public.orders (id, user_id, numero_pedido, cliente_nombre, cliente_telefono, cliente_correo, cliente_direccion, estado, total, total_iva, es_vip, notas, created_at, updated_at, cliente_documento) VALUES (4, 3, 'SC-6A29904763E3C', 'Carlos Moncayo', '0980951601', 'carlosm110206@gmail.com', 'UTN', 'en_proceso', 30.00, 0.00, false, 'Entregar al guardia', '2026-06-10 16:26:47', '2026-06-11 14:36:25', '1050343019');
INSERT INTO public.orders (id, user_id, numero_pedido, cliente_nombre, cliente_telefono, cliente_correo, cliente_direccion, estado, total, total_iva, es_vip, notas, created_at, updated_at, cliente_documento) VALUES (5, 8, 'SC-6A2AC938A40C5', 'Carlos', '0994780397', 'carlosg72quintana@gmail.com', 'carlosg72quintana@gmail.com
Imbabura', 'no_revisado', 415.00, 0.00, false, 'Ibarra', '2026-06-11 14:42:00', '2026-06-11 14:42:00', '1003907837');


--
-- Data for Name: products; Type: TABLE DATA; Schema: public; Owner: postgres
--

INSERT INTO public.products (id, nombre, slug, descripcion, imagen, category_id, precio_compra, precio_venta, iva_porcentaje, stock, stock_minimo, fecha_caducidad, activo, created_at, updated_at, deleted_at) VALUES (1, 'Gorra Gallo', 'gorra-gallo', 'Una hermosa gorra con un bordado de Gallo de Pelea', NULL, 1, 7.50, 15.00, 15.00, 20, 5, NULL, true, '2026-06-10 07:06:41', '2026-06-10 07:16:00', '2026-06-10 07:16:00');
INSERT INTO public.products (id, nombre, slug, descripcion, imagen, category_id, precio_compra, precio_venta, iva_porcentaje, stock, stock_minimo, fecha_caducidad, activo, created_at, updated_at, deleted_at) VALUES (3, 'Gorra Gallo', 'gorra-roja-gallo', 'Una hermosa gorra roja con bordado de gallo', 'products/2U4drfJWBvjTH66GEvPuXYIgXKPcZWhxZGt4imtS.webp', 1, 7.50, 15.00, 15.00, 18, 5, NULL, true, '2026-06-10 07:19:58', '2026-06-11 14:42:02', NULL);
INSERT INTO public.products (id, nombre, slug, descripcion, imagen, category_id, precio_compra, precio_venta, iva_porcentaje, stock, stock_minimo, fecha_caducidad, activo, created_at, updated_at, deleted_at) VALUES (4, 'Pantalla', 'pantalla', 'Una pantalla de 90 pulgadas', NULL, 5, 5.00, 200.00, 15.00, 28, 5, NULL, true, '2026-06-11 08:06:42', '2026-06-11 14:42:05', NULL);


--
-- Data for Name: order_items; Type: TABLE DATA; Schema: public; Owner: postgres
--

INSERT INTO public.order_items (id, order_id, product_id, cantidad, precio_unitario, iva_porcentaje, subtotal, created_at, updated_at) VALUES (2, 2, 3, 1, 15.00, 15.00, 15.00, '2026-06-10 07:51:01', '2026-06-10 07:51:01');
INSERT INTO public.order_items (id, order_id, product_id, cantidad, precio_unitario, iva_porcentaje, subtotal, created_at, updated_at) VALUES (3, 3, 3, 3, 15.00, 15.00, 45.00, '2026-06-10 08:41:22', '2026-06-10 08:41:22');
INSERT INTO public.order_items (id, order_id, product_id, cantidad, precio_unitario, iva_porcentaje, subtotal, created_at, updated_at) VALUES (4, 4, 3, 2, 15.00, 15.00, 30.00, '2026-06-10 16:26:48', '2026-06-10 16:26:48');
INSERT INTO public.order_items (id, order_id, product_id, cantidad, precio_unitario, iva_porcentaje, subtotal, created_at, updated_at) VALUES (5, 5, 3, 1, 15.00, 15.00, 15.00, '2026-06-11 14:42:01', '2026-06-11 14:42:01');
INSERT INTO public.order_items (id, order_id, product_id, cantidad, precio_unitario, iva_porcentaje, subtotal, created_at, updated_at) VALUES (6, 5, 4, 2, 200.00, 15.00, 400.00, '2026-06-11 14:42:03', '2026-06-11 14:42:03');


--
-- Data for Name: passkeys; Type: TABLE DATA; Schema: public; Owner: postgres
--



--
-- Data for Name: password_reset_tokens; Type: TABLE DATA; Schema: public; Owner: postgres
--



--
-- Data for Name: role_has_permissions; Type: TABLE DATA; Schema: public; Owner: postgres
--

INSERT INTO public.role_has_permissions (permission_id, role_id) VALUES (1, 1);
INSERT INTO public.role_has_permissions (permission_id, role_id) VALUES (2, 1);
INSERT INTO public.role_has_permissions (permission_id, role_id) VALUES (3, 2);
INSERT INTO public.role_has_permissions (permission_id, role_id) VALUES (4, 2);
INSERT INTO public.role_has_permissions (permission_id, role_id) VALUES (5, 4);


--
-- Data for Name: sessions; Type: TABLE DATA; Schema: public; Owner: postgres
--

INSERT INTO public.sessions (id, user_id, ip_address, user_agent, payload, last_activity) VALUES ('oidA201Vd0H4aaiEDGGonrZ4g9v96eDD1M2NhdPE', 6, '127.0.0.1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36', 'eyJfdG9rZW4iOiJsMUtCakdXeTJSZVVrbkc3OElZcXJrU2MxbnlvOFJlbVgxajJqMk8yIiwiX2ZsYXNoIjp7Im9sZCI6W10sIm5ldyI6W119LCJfcHJldmlvdXMiOnsidXJsIjoiaHR0cDpcL1wvMTI3LjAuMC4xOjgwMDBcL2xvZ2luIiwicm91dGUiOiJsb2dpbiJ9LCJsb2dpbl93ZWJfNTliYTM2YWRkYzJiMmY5NDAxNTgwZjAxNGM3ZjU4ZWE0ZTMwOTg5ZCI6Nn0=', 1781188367);
INSERT INTO public.sessions (id, user_id, ip_address, user_agent, payload, last_activity) VALUES ('msRj3IQc1iRK7pevoBciJI0DdfP4oJQQgKNtkDKY', NULL, '127.0.0.1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36', 'eyJfdG9rZW4iOiJDTHcwNzNpdTNGZWZKYkY5TjNhSzV1TVlMamhCWk53dGx1V1JTWVVEIiwiX2ZsYXNoIjp7Im9sZCI6W10sIm5ldyI6W119LCJfcHJldmlvdXMiOnsidXJsIjoiaHR0cDpcL1wvMTI3LjAuMC4xOjgwMDAiLCJyb3V0ZSI6InN0b3JlLmhvbWUifSwidXJsIjp7ImludGVuZGVkIjoiaHR0cDpcL1wvMTI3LjAuMC4xOjgwMDBcL2FkbWluXC9vcmRlcnMifX0=', 1781189852);
INSERT INTO public.sessions (id, user_id, ip_address, user_agent, payload, last_activity) VALUES ('1q0lZLxqR6mIH16huuC1OBbaPBR6pJ7q9G106HJJ', NULL, '127.0.0.1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36', 'eyJfdG9rZW4iOiJxZ0hHZzBPTzFIUnhPTDFpdGlDWUU1b0d1ZUhPSHAzNmxoaHBtRFYzIiwiX3ByZXZpb3VzIjp7InVybCI6Imh0dHA6XC9cLzEyNy4wLjAuMTo4MDAwIiwicm91dGUiOiJzdG9yZS5ob21lIn0sIl9mbGFzaCI6eyJvbGQiOltdLCJuZXciOltdfX0=', 1781185492);


--
-- Data for Name: settings; Type: TABLE DATA; Schema: public; Owner: postgres
--



--
-- Data for Name: schema_migrations; Type: TABLE DATA; Schema: realtime; Owner: supabase_admin
--

INSERT INTO realtime.schema_migrations (version, inserted_at) VALUES (20211116024918, '2026-06-03 21:57:42');
INSERT INTO realtime.schema_migrations (version, inserted_at) VALUES (20211116045059, '2026-06-03 21:57:42');
INSERT INTO realtime.schema_migrations (version, inserted_at) VALUES (20211116050929, '2026-06-03 21:57:43');
INSERT INTO realtime.schema_migrations (version, inserted_at) VALUES (20211116051442, '2026-06-03 21:57:43');
INSERT INTO realtime.schema_migrations (version, inserted_at) VALUES (20211116212300, '2026-06-03 21:57:43');
INSERT INTO realtime.schema_migrations (version, inserted_at) VALUES (20211116213355, '2026-06-03 21:57:43');
INSERT INTO realtime.schema_migrations (version, inserted_at) VALUES (20211116213934, '2026-06-03 21:57:43');
INSERT INTO realtime.schema_migrations (version, inserted_at) VALUES (20211116214523, '2026-06-03 21:57:44');
INSERT INTO realtime.schema_migrations (version, inserted_at) VALUES (20211122062447, '2026-06-03 21:57:44');
INSERT INTO realtime.schema_migrations (version, inserted_at) VALUES (20211124070109, '2026-06-03 21:57:44');
INSERT INTO realtime.schema_migrations (version, inserted_at) VALUES (20211202204204, '2026-06-03 21:57:44');
INSERT INTO realtime.schema_migrations (version, inserted_at) VALUES (20211202204605, '2026-06-03 21:57:44');
INSERT INTO realtime.schema_migrations (version, inserted_at) VALUES (20211210212804, '2026-06-03 21:57:45');
INSERT INTO realtime.schema_migrations (version, inserted_at) VALUES (20211228014915, '2026-06-03 21:57:45');
INSERT INTO realtime.schema_migrations (version, inserted_at) VALUES (20220107221237, '2026-06-03 21:57:45');
INSERT INTO realtime.schema_migrations (version, inserted_at) VALUES (20220228202821, '2026-06-03 21:57:45');
INSERT INTO realtime.schema_migrations (version, inserted_at) VALUES (20220312004840, '2026-06-03 21:57:46');
INSERT INTO realtime.schema_migrations (version, inserted_at) VALUES (20220603231003, '2026-06-03 21:57:46');
INSERT INTO realtime.schema_migrations (version, inserted_at) VALUES (20220603232444, '2026-06-03 21:57:46');
INSERT INTO realtime.schema_migrations (version, inserted_at) VALUES (20220615214548, '2026-06-03 21:57:46');
INSERT INTO realtime.schema_migrations (version, inserted_at) VALUES (20220712093339, '2026-06-03 21:57:47');
INSERT INTO realtime.schema_migrations (version, inserted_at) VALUES (20220908172859, '2026-06-03 21:57:47');
INSERT INTO realtime.schema_migrations (version, inserted_at) VALUES (20220916233421, '2026-06-03 21:57:47');
INSERT INTO realtime.schema_migrations (version, inserted_at) VALUES (20230119133233, '2026-06-03 21:57:47');
INSERT INTO realtime.schema_migrations (version, inserted_at) VALUES (20230128025114, '2026-06-03 21:57:47');
INSERT INTO realtime.schema_migrations (version, inserted_at) VALUES (20230128025212, '2026-06-03 21:57:48');
INSERT INTO realtime.schema_migrations (version, inserted_at) VALUES (20230227211149, '2026-06-03 21:57:48');
INSERT INTO realtime.schema_migrations (version, inserted_at) VALUES (20230228184745, '2026-06-03 21:57:48');
INSERT INTO realtime.schema_migrations (version, inserted_at) VALUES (20230308225145, '2026-06-03 21:57:48');
INSERT INTO realtime.schema_migrations (version, inserted_at) VALUES (20230328144023, '2026-06-03 21:57:48');
INSERT INTO realtime.schema_migrations (version, inserted_at) VALUES (20231018144023, '2026-06-03 21:57:49');
INSERT INTO realtime.schema_migrations (version, inserted_at) VALUES (20231204144023, '2026-06-03 21:57:49');
INSERT INTO realtime.schema_migrations (version, inserted_at) VALUES (20231204144024, '2026-06-03 21:57:49');
INSERT INTO realtime.schema_migrations (version, inserted_at) VALUES (20231204144025, '2026-06-03 21:57:49');
INSERT INTO realtime.schema_migrations (version, inserted_at) VALUES (20240108234812, '2026-06-03 21:57:49');
INSERT INTO realtime.schema_migrations (version, inserted_at) VALUES (20240109165339, '2026-06-03 21:57:50');
INSERT INTO realtime.schema_migrations (version, inserted_at) VALUES (20240227174441, '2026-06-03 21:57:50');
INSERT INTO realtime.schema_migrations (version, inserted_at) VALUES (20240311171622, '2026-06-03 21:57:50');
INSERT INTO realtime.schema_migrations (version, inserted_at) VALUES (20240321100241, '2026-06-03 21:57:51');
INSERT INTO realtime.schema_migrations (version, inserted_at) VALUES (20240401105812, '2026-06-03 21:57:51');
INSERT INTO realtime.schema_migrations (version, inserted_at) VALUES (20240418121054, '2026-06-03 21:57:51');
INSERT INTO realtime.schema_migrations (version, inserted_at) VALUES (20240523004032, '2026-06-03 21:57:52');
INSERT INTO realtime.schema_migrations (version, inserted_at) VALUES (20240618124746, '2026-06-03 21:57:52');
INSERT INTO realtime.schema_migrations (version, inserted_at) VALUES (20240801235015, '2026-06-03 21:57:52');
INSERT INTO realtime.schema_migrations (version, inserted_at) VALUES (20240805133720, '2026-06-03 21:57:53');
INSERT INTO realtime.schema_migrations (version, inserted_at) VALUES (20240827160934, '2026-06-03 21:57:53');
INSERT INTO realtime.schema_migrations (version, inserted_at) VALUES (20240919163303, '2026-06-03 21:57:53');
INSERT INTO realtime.schema_migrations (version, inserted_at) VALUES (20240919163305, '2026-06-03 21:57:53');
INSERT INTO realtime.schema_migrations (version, inserted_at) VALUES (20241019105805, '2026-06-03 21:57:53');
INSERT INTO realtime.schema_migrations (version, inserted_at) VALUES (20241030150047, '2026-06-03 21:57:54');
INSERT INTO realtime.schema_migrations (version, inserted_at) VALUES (20241108114728, '2026-06-03 21:57:54');
INSERT INTO realtime.schema_migrations (version, inserted_at) VALUES (20241121104152, '2026-06-03 21:57:55');
INSERT INTO realtime.schema_migrations (version, inserted_at) VALUES (20241130184212, '2026-06-03 21:57:55');
INSERT INTO realtime.schema_migrations (version, inserted_at) VALUES (20241220035512, '2026-06-03 21:57:55');
INSERT INTO realtime.schema_migrations (version, inserted_at) VALUES (20241220123912, '2026-06-03 21:57:55');
INSERT INTO realtime.schema_migrations (version, inserted_at) VALUES (20241224161212, '2026-06-03 21:57:55');
INSERT INTO realtime.schema_migrations (version, inserted_at) VALUES (20250107150512, '2026-06-03 21:57:56');
INSERT INTO realtime.schema_migrations (version, inserted_at) VALUES (20250110162412, '2026-06-03 21:57:56');
INSERT INTO realtime.schema_migrations (version, inserted_at) VALUES (20250123174212, '2026-06-03 21:57:56');
INSERT INTO realtime.schema_migrations (version, inserted_at) VALUES (20250128220012, '2026-06-03 21:57:56');
INSERT INTO realtime.schema_migrations (version, inserted_at) VALUES (20250506224012, '2026-06-03 21:57:56');
INSERT INTO realtime.schema_migrations (version, inserted_at) VALUES (20250523164012, '2026-06-03 21:57:56');
INSERT INTO realtime.schema_migrations (version, inserted_at) VALUES (20250714121412, '2026-06-03 21:57:57');
INSERT INTO realtime.schema_migrations (version, inserted_at) VALUES (20250905041441, '2026-06-03 21:57:57');
INSERT INTO realtime.schema_migrations (version, inserted_at) VALUES (20251103001201, '2026-06-03 21:57:57');
INSERT INTO realtime.schema_migrations (version, inserted_at) VALUES (20251120212548, '2026-06-03 21:57:57');
INSERT INTO realtime.schema_migrations (version, inserted_at) VALUES (20251120215549, '2026-06-03 21:57:57');
INSERT INTO realtime.schema_migrations (version, inserted_at) VALUES (20260218120000, '2026-06-03 21:57:58');
INSERT INTO realtime.schema_migrations (version, inserted_at) VALUES (20260326120000, '2026-06-03 21:57:58');
INSERT INTO realtime.schema_migrations (version, inserted_at) VALUES (20260514120000, '2026-06-03 21:57:58');
INSERT INTO realtime.schema_migrations (version, inserted_at) VALUES (20260527120000, '2026-06-03 21:57:59');
INSERT INTO realtime.schema_migrations (version, inserted_at) VALUES (20260528120000, '2026-06-03 21:57:59');
INSERT INTO realtime.schema_migrations (version, inserted_at) VALUES (20260603120000, '2026-06-03 21:57:59');


--
-- Data for Name: subscription; Type: TABLE DATA; Schema: realtime; Owner: supabase_admin
--



--
-- Data for Name: buckets; Type: TABLE DATA; Schema: storage; Owner: supabase_storage_admin
--



--
-- Data for Name: buckets_analytics; Type: TABLE DATA; Schema: storage; Owner: supabase_storage_admin
--



--
-- Data for Name: buckets_vectors; Type: TABLE DATA; Schema: storage; Owner: supabase_storage_admin
--



--
-- Data for Name: migrations; Type: TABLE DATA; Schema: storage; Owner: supabase_storage_admin
--

INSERT INTO storage.migrations (id, name, hash, executed_at) VALUES (0, 'create-migrations-table', 'e18db593bcde2aca2a408c4d1100f6abba2195df', '2026-06-03 21:57:43.905195');
INSERT INTO storage.migrations (id, name, hash, executed_at) VALUES (1, 'initialmigration', '6ab16121fbaa08bbd11b712d05f358f9b555d777', '2026-06-03 21:57:43.914341');
INSERT INTO storage.migrations (id, name, hash, executed_at) VALUES (2, 'storage-schema', 'f6a1fa2c93cbcd16d4e487b362e45fca157a8dbd', '2026-06-03 21:57:43.917976');
INSERT INTO storage.migrations (id, name, hash, executed_at) VALUES (3, 'pathtoken-column', '2cb1b0004b817b29d5b0a971af16bafeede4b70d', '2026-06-03 21:57:43.93054');
INSERT INTO storage.migrations (id, name, hash, executed_at) VALUES (4, 'add-migrations-rls', '427c5b63fe1c5937495d9c635c263ee7a5905058', '2026-06-03 21:57:43.947517');
INSERT INTO storage.migrations (id, name, hash, executed_at) VALUES (5, 'add-size-functions', '79e081a1455b63666c1294a440f8ad4b1e6a7f84', '2026-06-03 21:57:43.951216');
INSERT INTO storage.migrations (id, name, hash, executed_at) VALUES (6, 'change-column-name-in-get-size', 'ded78e2f1b5d7e616117897e6443a925965b30d2', '2026-06-03 21:57:43.954925');
INSERT INTO storage.migrations (id, name, hash, executed_at) VALUES (7, 'add-rls-to-buckets', 'e7e7f86adbc51049f341dfe8d30256c1abca17aa', '2026-06-03 21:57:43.958816');
INSERT INTO storage.migrations (id, name, hash, executed_at) VALUES (8, 'add-public-to-buckets', 'fd670db39ed65f9d08b01db09d6202503ca2bab3', '2026-06-03 21:57:43.962288');
INSERT INTO storage.migrations (id, name, hash, executed_at) VALUES (9, 'fix-search-function', 'af597a1b590c70519b464a4ab3be54490712796b', '2026-06-03 21:57:43.966545');
INSERT INTO storage.migrations (id, name, hash, executed_at) VALUES (10, 'search-files-search-function', 'b595f05e92f7e91211af1bbfe9c6a13bb3391e16', '2026-06-03 21:57:43.969932');
INSERT INTO storage.migrations (id, name, hash, executed_at) VALUES (11, 'add-trigger-to-auto-update-updated_at-column', '7425bdb14366d1739fa8a18c83100636d74dcaa2', '2026-06-03 21:57:43.973399');
INSERT INTO storage.migrations (id, name, hash, executed_at) VALUES (12, 'add-automatic-avif-detection-flag', '8e92e1266eb29518b6a4c5313ab8f29dd0d08df9', '2026-06-03 21:57:43.97713');
INSERT INTO storage.migrations (id, name, hash, executed_at) VALUES (13, 'add-bucket-custom-limits', 'cce962054138135cd9a8c4bcd531598684b25e7d', '2026-06-03 21:57:43.980449');
INSERT INTO storage.migrations (id, name, hash, executed_at) VALUES (14, 'use-bytes-for-max-size', '941c41b346f9802b411f06f30e972ad4744dad27', '2026-06-03 21:57:43.983898');
INSERT INTO storage.migrations (id, name, hash, executed_at) VALUES (15, 'add-can-insert-object-function', '934146bc38ead475f4ef4b555c524ee5d66799e5', '2026-06-03 21:57:44.008267');
INSERT INTO storage.migrations (id, name, hash, executed_at) VALUES (16, 'add-version', '76debf38d3fd07dcfc747ca49096457d95b1221b', '2026-06-03 21:57:44.011713');
INSERT INTO storage.migrations (id, name, hash, executed_at) VALUES (17, 'drop-owner-foreign-key', 'f1cbb288f1b7a4c1eb8c38504b80ae2a0153d101', '2026-06-03 21:57:44.015442');
INSERT INTO storage.migrations (id, name, hash, executed_at) VALUES (18, 'add_owner_id_column_deprecate_owner', 'e7a511b379110b08e2f214be852c35414749fe66', '2026-06-03 21:57:44.018915');
INSERT INTO storage.migrations (id, name, hash, executed_at) VALUES (19, 'alter-default-value-objects-id', '02e5e22a78626187e00d173dc45f58fa66a4f043', '2026-06-03 21:57:44.02365');
INSERT INTO storage.migrations (id, name, hash, executed_at) VALUES (20, 'list-objects-with-delimiter', 'cd694ae708e51ba82bf012bba00caf4f3b6393b7', '2026-06-03 21:57:44.027165');
INSERT INTO storage.migrations (id, name, hash, executed_at) VALUES (21, 's3-multipart-uploads', '8c804d4a566c40cd1e4cc5b3725a664a9303657f', '2026-06-03 21:57:44.032107');
INSERT INTO storage.migrations (id, name, hash, executed_at) VALUES (22, 's3-multipart-uploads-big-ints', '9737dc258d2397953c9953d9b86920b8be0cdb73', '2026-06-03 21:57:44.043019');
INSERT INTO storage.migrations (id, name, hash, executed_at) VALUES (23, 'optimize-search-function', '9d7e604cddc4b56a5422dc68c9313f4a1b6f132c', '2026-06-03 21:57:44.051739');
INSERT INTO storage.migrations (id, name, hash, executed_at) VALUES (24, 'operation-function', '8312e37c2bf9e76bbe841aa5fda889206d2bf8aa', '2026-06-03 21:57:44.057433');
INSERT INTO storage.migrations (id, name, hash, executed_at) VALUES (25, 'custom-metadata', 'd974c6057c3db1c1f847afa0e291e6165693b990', '2026-06-03 21:57:44.060873');
INSERT INTO storage.migrations (id, name, hash, executed_at) VALUES (26, 'objects-prefixes', '215cabcb7f78121892a5a2037a09fedf9a1ae322', '2026-06-03 21:57:44.064677');
INSERT INTO storage.migrations (id, name, hash, executed_at) VALUES (27, 'search-v2', '859ba38092ac96eb3964d83bf53ccc0b141663a6', '2026-06-03 21:57:44.068556');
INSERT INTO storage.migrations (id, name, hash, executed_at) VALUES (28, 'object-bucket-name-sorting', 'c73a2b5b5d4041e39705814fd3a1b95502d38ce4', '2026-06-03 21:57:44.071768');
INSERT INTO storage.migrations (id, name, hash, executed_at) VALUES (29, 'create-prefixes', 'ad2c1207f76703d11a9f9007f821620017a66c21', '2026-06-03 21:57:44.074858');
INSERT INTO storage.migrations (id, name, hash, executed_at) VALUES (30, 'update-object-levels', '2be814ff05c8252fdfdc7cfb4b7f5c7e17f0bed6', '2026-06-03 21:57:44.077839');
INSERT INTO storage.migrations (id, name, hash, executed_at) VALUES (31, 'objects-level-index', 'b40367c14c3440ec75f19bbce2d71e914ddd3da0', '2026-06-03 21:57:44.080769');
INSERT INTO storage.migrations (id, name, hash, executed_at) VALUES (32, 'backward-compatible-index-on-objects', 'e0c37182b0f7aee3efd823298fb3c76f1042c0f7', '2026-06-03 21:57:44.08473');
INSERT INTO storage.migrations (id, name, hash, executed_at) VALUES (33, 'backward-compatible-index-on-prefixes', 'b480e99ed951e0900f033ec4eb34b5bdcb4e3d49', '2026-06-03 21:57:44.087851');
INSERT INTO storage.migrations (id, name, hash, executed_at) VALUES (34, 'optimize-search-function-v1', 'ca80a3dc7bfef894df17108785ce29a7fc8ee456', '2026-06-03 21:57:44.0909');
INSERT INTO storage.migrations (id, name, hash, executed_at) VALUES (35, 'add-insert-trigger-prefixes', '458fe0ffd07ec53f5e3ce9df51bfdf4861929ccc', '2026-06-03 21:57:44.094808');
INSERT INTO storage.migrations (id, name, hash, executed_at) VALUES (36, 'optimise-existing-functions', '6ae5fca6af5c55abe95369cd4f93985d1814ca8f', '2026-06-03 21:57:44.097715');
INSERT INTO storage.migrations (id, name, hash, executed_at) VALUES (37, 'add-bucket-name-length-trigger', '3944135b4e3e8b22d6d4cbb568fe3b0b51df15c1', '2026-06-03 21:57:44.100767');
INSERT INTO storage.migrations (id, name, hash, executed_at) VALUES (38, 'iceberg-catalog-flag-on-buckets', '02716b81ceec9705aed84aa1501657095b32e5c5', '2026-06-03 21:57:44.104621');
INSERT INTO storage.migrations (id, name, hash, executed_at) VALUES (39, 'add-search-v2-sort-support', '6706c5f2928846abee18461279799ad12b279b78', '2026-06-03 21:57:44.112744');
INSERT INTO storage.migrations (id, name, hash, executed_at) VALUES (40, 'fix-prefix-race-conditions-optimized', '7ad69982ae2d372b21f48fc4829ae9752c518f6b', '2026-06-03 21:57:44.117302');
INSERT INTO storage.migrations (id, name, hash, executed_at) VALUES (41, 'add-object-level-update-trigger', '07fcf1a22165849b7a029deed059ffcde08d1ae0', '2026-06-03 21:57:44.120304');
INSERT INTO storage.migrations (id, name, hash, executed_at) VALUES (42, 'rollback-prefix-triggers', '771479077764adc09e2ea2043eb627503c034cd4', '2026-06-03 21:57:44.123263');
INSERT INTO storage.migrations (id, name, hash, executed_at) VALUES (43, 'fix-object-level', '84b35d6caca9d937478ad8a797491f38b8c2979f', '2026-06-03 21:57:44.126087');
INSERT INTO storage.migrations (id, name, hash, executed_at) VALUES (44, 'vector-bucket-type', '99c20c0ffd52bb1ff1f32fb992f3b351e3ef8fb3', '2026-06-03 21:57:44.12951');
INSERT INTO storage.migrations (id, name, hash, executed_at) VALUES (45, 'vector-buckets', '049e27196d77a7cb76497a85afae669d8b230953', '2026-06-03 21:57:44.133865');
INSERT INTO storage.migrations (id, name, hash, executed_at) VALUES (46, 'buckets-objects-grants', 'fedeb96d60fefd8e02ab3ded9fbde05632f84aed', '2026-06-03 21:57:44.143614');
INSERT INTO storage.migrations (id, name, hash, executed_at) VALUES (47, 'iceberg-table-metadata', '649df56855c24d8b36dd4cc1aeb8251aa9ad42c2', '2026-06-03 21:57:44.14772');
INSERT INTO storage.migrations (id, name, hash, executed_at) VALUES (48, 'iceberg-catalog-ids', 'e0e8b460c609b9999ccd0df9ad14294613eed939', '2026-06-03 21:57:44.15129');
INSERT INTO storage.migrations (id, name, hash, executed_at) VALUES (49, 'buckets-objects-grants-postgres', '072b1195d0d5a2f888af6b2302a1938dd94b8b3d', '2026-06-03 21:57:44.167258');
INSERT INTO storage.migrations (id, name, hash, executed_at) VALUES (50, 'search-v2-optimised', '6323ac4f850aa14e7387eb32102869578b5bd478', '2026-06-03 21:57:44.173342');
INSERT INTO storage.migrations (id, name, hash, executed_at) VALUES (51, 'index-backward-compatible-search', '2ee395d433f76e38bcd3856debaf6e0e5b674011', '2026-06-03 21:57:44.22532');
INSERT INTO storage.migrations (id, name, hash, executed_at) VALUES (52, 'drop-not-used-indexes-and-functions', '5cc44c8696749ac11dd0dc37f2a3802075f3a171', '2026-06-03 21:57:44.226963');
INSERT INTO storage.migrations (id, name, hash, executed_at) VALUES (53, 'drop-index-lower-name', 'd0cb18777d9e2a98ebe0bc5cc7a42e57ebe41854', '2026-06-03 21:57:44.235864');
INSERT INTO storage.migrations (id, name, hash, executed_at) VALUES (54, 'drop-index-object-level', '6289e048b1472da17c31a7eba1ded625a6457e67', '2026-06-03 21:57:44.238132');
INSERT INTO storage.migrations (id, name, hash, executed_at) VALUES (55, 'prevent-direct-deletes', '262a4798d5e0f2e7c8970232e03ce8be695d5819', '2026-06-03 21:57:44.23961');
INSERT INTO storage.migrations (id, name, hash, executed_at) VALUES (56, 'fix-optimized-search-function', 'b823ed1e418101032fa01374edc9a436e54e3ed4', '2026-06-03 21:57:44.243834');
INSERT INTO storage.migrations (id, name, hash, executed_at) VALUES (57, 's3-multipart-uploads-metadata', 'f127886e00d1b374fadbc7c6b31e09336aad5287', '2026-06-03 21:57:44.248432');
INSERT INTO storage.migrations (id, name, hash, executed_at) VALUES (58, 'operation-ergonomics', '00ca5d483b3fe0d522133d9002ccc5df98365120', '2026-06-03 21:57:44.2523');
INSERT INTO storage.migrations (id, name, hash, executed_at) VALUES (59, 'drop-unused-functions', '38456f13e39691c2bbb4b5151d0d1cdbabd4a8c4', '2026-06-03 21:57:44.256494');
INSERT INTO storage.migrations (id, name, hash, executed_at) VALUES (60, 'optimize-existing-functions-again', 'db35e1c91a9201e59f4fef8d972c2f277d68b157', '2026-06-03 21:57:44.26036');


--
-- Data for Name: objects; Type: TABLE DATA; Schema: storage; Owner: supabase_storage_admin
--



--
-- Data for Name: s3_multipart_uploads; Type: TABLE DATA; Schema: storage; Owner: supabase_storage_admin
--



--
-- Data for Name: s3_multipart_uploads_parts; Type: TABLE DATA; Schema: storage; Owner: supabase_storage_admin
--



--
-- Data for Name: vector_indexes; Type: TABLE DATA; Schema: storage; Owner: supabase_storage_admin
--



--
-- Data for Name: secrets; Type: TABLE DATA; Schema: vault; Owner: supabase_admin
--



--
-- Name: refresh_tokens_id_seq; Type: SEQUENCE SET; Schema: auth; Owner: supabase_auth_admin
--

SELECT pg_catalog.setval('auth.refresh_tokens_id_seq', 1, false);


--
-- Name: categories_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.categories_id_seq', 7, true);


--
-- Name: failed_jobs_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.failed_jobs_id_seq', 1, false);


--
-- Name: jobs_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.jobs_id_seq', 1, false);


--
-- Name: migrations_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.migrations_id_seq', 13, true);


--
-- Name: order_items_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.order_items_id_seq', 6, true);


--
-- Name: orders_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.orders_id_seq', 5, true);


--
-- Name: passkeys_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.passkeys_id_seq', 1, false);


--
-- Name: permissions_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.permissions_id_seq', 5, true);


--
-- Name: products_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.products_id_seq', 4, true);


--
-- Name: roles_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.roles_id_seq', 4, true);


--
-- Name: settings_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.settings_id_seq', 1, false);


--
-- Name: users_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.users_id_seq', 8, true);


--
-- Name: subscription_id_seq; Type: SEQUENCE SET; Schema: realtime; Owner: supabase_admin
--

SELECT pg_catalog.setval('realtime.subscription_id_seq', 1, false);


--
-- PostgreSQL database dump complete
--

\unrestrict d5gl9T7N6Ys1YN8W8TRVXt9NeVQ4IaaKKCHFB7bLShfmqhU1L5AUkOWEIYgs47x

