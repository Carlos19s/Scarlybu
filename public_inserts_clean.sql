--
-- PostgreSQL database dump
--

\restrict 4vuRVpBLtx7XYTHgtppjGc8rz1df8HFvctls4l45grczVLoRQR7uh3FxnKTcYBy

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
-- PostgreSQL database dump complete
--

\unrestrict 4vuRVpBLtx7XYTHgtppjGc8rz1df8HFvctls4l45grczVLoRQR7uh3FxnKTcYBy

