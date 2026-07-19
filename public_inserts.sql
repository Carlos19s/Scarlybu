
DROP SCHEMA IF EXISTS public CASCADE;
CREATE SCHEMA public;
GRANT ALL ON SCHEMA public TO postgres;
GRANT ALL ON SCHEMA public TO public;

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

SET default_tablespace = '';

SET default_table_access_method = heap;

CREATE TABLE public.cache (
    key character varying(255) NOT NULL,
    value text NOT NULL,
    expiration bigint NOT NULL
);

ALTER TABLE public.cache OWNER TO postgres;

CREATE TABLE public.cache_locks (
    key character varying(255) NOT NULL,
    owner character varying(255) NOT NULL,
    expiration bigint NOT NULL
);

ALTER TABLE public.cache_locks OWNER TO postgres;

CREATE TABLE public.categories (
    id bigint NOT NULL,
    nombre character varying(255) NOT NULL,
    slug character varying(255) NOT NULL,
    descripcion text,
    parent_id bigint,
    imagen character varying(255),
    activa boolean DEFAULT true NOT NULL,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone
);

ALTER TABLE public.categories OWNER TO postgres;

CREATE SEQUENCE public.categories_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;

ALTER SEQUENCE public.categories_id_seq OWNER TO postgres;

ALTER SEQUENCE public.categories_id_seq OWNED BY public.categories.id;

CREATE TABLE public.failed_jobs (
    id bigint NOT NULL,
    uuid character varying(255) NOT NULL,
    connection character varying(255) NOT NULL,
    queue character varying(255) NOT NULL,
    payload text NOT NULL,
    exception text NOT NULL,
    failed_at timestamp(0) without time zone DEFAULT CURRENT_TIMESTAMP NOT NULL
);

ALTER TABLE public.failed_jobs OWNER TO postgres;

CREATE SEQUENCE public.failed_jobs_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;

ALTER SEQUENCE public.failed_jobs_id_seq OWNER TO postgres;

ALTER SEQUENCE public.failed_jobs_id_seq OWNED BY public.failed_jobs.id;

CREATE TABLE public.job_batches (
    id character varying(255) NOT NULL,
    name character varying(255) NOT NULL,
    total_jobs integer NOT NULL,
    pending_jobs integer NOT NULL,
    failed_jobs integer NOT NULL,
    failed_job_ids text NOT NULL,
    options text,
    cancelled_at integer,
    created_at integer NOT NULL,
    finished_at integer
);

ALTER TABLE public.job_batches OWNER TO postgres;

CREATE TABLE public.jobs (
    id bigint NOT NULL,
    queue character varying(255) NOT NULL,
    payload text NOT NULL,
    attempts smallint NOT NULL,
    reserved_at integer,
    available_at integer NOT NULL,
    created_at integer NOT NULL
);

ALTER TABLE public.jobs OWNER TO postgres;

CREATE SEQUENCE public.jobs_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;

ALTER SEQUENCE public.jobs_id_seq OWNER TO postgres;

ALTER SEQUENCE public.jobs_id_seq OWNED BY public.jobs.id;

CREATE TABLE public.migrations (
    id integer NOT NULL,
    migration character varying(255) NOT NULL,
    batch integer NOT NULL
);

ALTER TABLE public.migrations OWNER TO postgres;

CREATE SEQUENCE public.migrations_id_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;

ALTER SEQUENCE public.migrations_id_seq OWNER TO postgres;

ALTER SEQUENCE public.migrations_id_seq OWNED BY public.migrations.id;

CREATE TABLE public.model_has_permissions (
    permission_id bigint NOT NULL,
    model_type character varying(255) NOT NULL,
    model_id bigint NOT NULL
);

ALTER TABLE public.model_has_permissions OWNER TO postgres;

CREATE TABLE public.model_has_roles (
    role_id bigint NOT NULL,
    model_type character varying(255) NOT NULL,
    model_id bigint NOT NULL
);

ALTER TABLE public.model_has_roles OWNER TO postgres;

CREATE TABLE public.order_items (
    id bigint NOT NULL,
    order_id bigint NOT NULL,
    product_id bigint NOT NULL,
    cantidad integer NOT NULL,
    precio_unitario numeric(10,2) NOT NULL,
    iva_porcentaje numeric(5,2) NOT NULL,
    subtotal numeric(12,2) NOT NULL,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone
);

ALTER TABLE public.order_items OWNER TO postgres;

CREATE SEQUENCE public.order_items_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;

ALTER SEQUENCE public.order_items_id_seq OWNER TO postgres;

ALTER SEQUENCE public.order_items_id_seq OWNED BY public.order_items.id;

CREATE TABLE public.orders (
    id bigint NOT NULL,
    user_id bigint NOT NULL,
    numero_pedido character varying(255) NOT NULL,
    cliente_nombre character varying(255) NOT NULL,
    cliente_telefono character varying(255) NOT NULL,
    cliente_correo character varying(255),
    cliente_direccion text NOT NULL,
    estado character varying(255) DEFAULT 'no_revisado'::character varying NOT NULL,
    total numeric(12,2) DEFAULT '0'::numeric NOT NULL,
    total_iva numeric(12,2) DEFAULT '0'::numeric NOT NULL,
    es_vip boolean DEFAULT false NOT NULL,
    notas text,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone,
    cliente_documento character varying(255)
);

ALTER TABLE public.orders OWNER TO postgres;

CREATE SEQUENCE public.orders_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;

ALTER SEQUENCE public.orders_id_seq OWNER TO postgres;

ALTER SEQUENCE public.orders_id_seq OWNED BY public.orders.id;

CREATE TABLE public.passkeys (
    id bigint NOT NULL,
    user_id bigint NOT NULL,
    name character varying(255) NOT NULL,
    credential_id character varying(255) NOT NULL,
    credential json NOT NULL,
    last_used_at timestamp(0) without time zone,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone
);

ALTER TABLE public.passkeys OWNER TO postgres;

CREATE SEQUENCE public.passkeys_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;

ALTER SEQUENCE public.passkeys_id_seq OWNER TO postgres;

ALTER SEQUENCE public.passkeys_id_seq OWNED BY public.passkeys.id;

CREATE TABLE public.password_reset_tokens (
    email character varying(255) NOT NULL,
    token character varying(255) NOT NULL,
    created_at timestamp(0) without time zone
);

ALTER TABLE public.password_reset_tokens OWNER TO postgres;

CREATE TABLE public.permissions (
    id bigint NOT NULL,
    name character varying(255) NOT NULL,
    guard_name character varying(255) NOT NULL,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone
);

ALTER TABLE public.permissions OWNER TO postgres;

CREATE SEQUENCE public.permissions_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;

ALTER SEQUENCE public.permissions_id_seq OWNER TO postgres;

ALTER SEQUENCE public.permissions_id_seq OWNED BY public.permissions.id;

CREATE TABLE public.products (
    id bigint NOT NULL,
    nombre character varying(255) NOT NULL,
    slug character varying(255) NOT NULL,
    descripcion text,
    imagen character varying(255),
    category_id bigint NOT NULL,
    precio_compra numeric(10,2) NOT NULL,
    precio_venta numeric(10,2) NOT NULL,
    iva_porcentaje numeric(5,2) DEFAULT '16'::numeric NOT NULL,
    stock integer DEFAULT 0 NOT NULL,
    stock_minimo integer DEFAULT 5 NOT NULL,
    fecha_caducidad date,
    activo boolean DEFAULT true NOT NULL,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone,
    deleted_at timestamp(0) without time zone
);

ALTER TABLE public.products OWNER TO postgres;

CREATE SEQUENCE public.products_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;

ALTER SEQUENCE public.products_id_seq OWNER TO postgres;

ALTER SEQUENCE public.products_id_seq OWNED BY public.products.id;

CREATE TABLE public.promociones (
    id bigint NOT NULL,
    product_id bigint NOT NULL,
    precio_promocion numeric(10,2) NOT NULL,
    fecha_inicio date NOT NULL,
    fecha_fin date NOT NULL,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone
);

ALTER TABLE public.promociones OWNER TO postgres;

CREATE SEQUENCE public.promociones_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;

ALTER SEQUENCE public.promociones_id_seq OWNER TO postgres;

ALTER SEQUENCE public.promociones_id_seq OWNED BY public.promociones.id;

CREATE TABLE public.role_has_permissions (
    permission_id bigint NOT NULL,
    role_id bigint NOT NULL
);

ALTER TABLE public.role_has_permissions OWNER TO postgres;

CREATE TABLE public.roles (
    id bigint NOT NULL,
    name character varying(255) NOT NULL,
    guard_name character varying(255) NOT NULL,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone
);

ALTER TABLE public.roles OWNER TO postgres;

CREATE SEQUENCE public.roles_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;

ALTER SEQUENCE public.roles_id_seq OWNER TO postgres;

ALTER SEQUENCE public.roles_id_seq OWNED BY public.roles.id;

CREATE TABLE public.sessions (
    id character varying(255) NOT NULL,
    user_id bigint,
    ip_address character varying(45),
    user_agent text,
    payload text NOT NULL,
    last_activity integer NOT NULL
);

ALTER TABLE public.sessions OWNER TO postgres;

CREATE TABLE public.settings (
    id bigint NOT NULL,
    key character varying(255) NOT NULL,
    value text,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone
);

ALTER TABLE public.settings OWNER TO postgres;

CREATE SEQUENCE public.settings_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;

ALTER SEQUENCE public.settings_id_seq OWNER TO postgres;

ALTER SEQUENCE public.settings_id_seq OWNED BY public.settings.id;

CREATE TABLE public.users (
    id bigint NOT NULL,
    name character varying(255) NOT NULL,
    email character varying(255) NOT NULL,
    email_verified_at timestamp(0) without time zone,
    password character varying(255) NOT NULL,
    remember_token character varying(100),
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone,
    two_factor_secret text,
    two_factor_recovery_codes text,
    two_factor_confirmed_at timestamp(0) without time zone,
    role character varying(255) DEFAULT 'vendedor'::character varying NOT NULL
);

ALTER TABLE public.users OWNER TO postgres;

CREATE SEQUENCE public.users_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;

ALTER SEQUENCE public.users_id_seq OWNER TO postgres;

ALTER SEQUENCE public.users_id_seq OWNED BY public.users.id;

ALTER TABLE ONLY public.categories ALTER COLUMN id SET DEFAULT nextval('public.categories_id_seq'::regclass);

ALTER TABLE ONLY public.failed_jobs ALTER COLUMN id SET DEFAULT nextval('public.failed_jobs_id_seq'::regclass);

ALTER TABLE ONLY public.jobs ALTER COLUMN id SET DEFAULT nextval('public.jobs_id_seq'::regclass);

ALTER TABLE ONLY public.migrations ALTER COLUMN id SET DEFAULT nextval('public.migrations_id_seq'::regclass);

ALTER TABLE ONLY public.order_items ALTER COLUMN id SET DEFAULT nextval('public.order_items_id_seq'::regclass);

ALTER TABLE ONLY public.orders ALTER COLUMN id SET DEFAULT nextval('public.orders_id_seq'::regclass);

ALTER TABLE ONLY public.passkeys ALTER COLUMN id SET DEFAULT nextval('public.passkeys_id_seq'::regclass);

ALTER TABLE ONLY public.permissions ALTER COLUMN id SET DEFAULT nextval('public.permissions_id_seq'::regclass);

ALTER TABLE ONLY public.products ALTER COLUMN id SET DEFAULT nextval('public.products_id_seq'::regclass);

ALTER TABLE ONLY public.promociones ALTER COLUMN id SET DEFAULT nextval('public.promociones_id_seq'::regclass);

ALTER TABLE ONLY public.roles ALTER COLUMN id SET DEFAULT nextval('public.roles_id_seq'::regclass);

ALTER TABLE ONLY public.settings ALTER COLUMN id SET DEFAULT nextval('public.settings_id_seq'::regclass);

ALTER TABLE ONLY public.users ALTER COLUMN id SET DEFAULT nextval('public.users_id_seq'::regclass);

ALTER TABLE ONLY public.cache_locks
    ADD CONSTRAINT cache_locks_pkey PRIMARY KEY (key);

ALTER TABLE ONLY public.cache
    ADD CONSTRAINT cache_pkey PRIMARY KEY (key);

ALTER TABLE ONLY public.categories
    ADD CONSTRAINT categories_pkey PRIMARY KEY (id);

ALTER TABLE ONLY public.categories
    ADD CONSTRAINT categories_slug_unique UNIQUE (slug);

ALTER TABLE ONLY public.failed_jobs
    ADD CONSTRAINT failed_jobs_pkey PRIMARY KEY (id);

ALTER TABLE ONLY public.failed_jobs
    ADD CONSTRAINT failed_jobs_uuid_unique UNIQUE (uuid);

ALTER TABLE ONLY public.job_batches
    ADD CONSTRAINT job_batches_pkey PRIMARY KEY (id);

ALTER TABLE ONLY public.jobs
    ADD CONSTRAINT jobs_pkey PRIMARY KEY (id);

ALTER TABLE ONLY public.migrations
    ADD CONSTRAINT migrations_pkey PRIMARY KEY (id);

ALTER TABLE ONLY public.model_has_permissions
    ADD CONSTRAINT model_has_permissions_pkey PRIMARY KEY (permission_id, model_id, model_type);

ALTER TABLE ONLY public.model_has_roles
    ADD CONSTRAINT model_has_roles_pkey PRIMARY KEY (role_id, model_id, model_type);

ALTER TABLE ONLY public.order_items
    ADD CONSTRAINT order_items_pkey PRIMARY KEY (id);

ALTER TABLE ONLY public.orders
    ADD CONSTRAINT orders_numero_pedido_unique UNIQUE (numero_pedido);

ALTER TABLE ONLY public.orders
    ADD CONSTRAINT orders_pkey PRIMARY KEY (id);

ALTER TABLE ONLY public.passkeys
    ADD CONSTRAINT passkeys_credential_id_unique UNIQUE (credential_id);

ALTER TABLE ONLY public.passkeys
    ADD CONSTRAINT passkeys_pkey PRIMARY KEY (id);

ALTER TABLE ONLY public.password_reset_tokens
    ADD CONSTRAINT password_reset_tokens_pkey PRIMARY KEY (email);

ALTER TABLE ONLY public.permissions
    ADD CONSTRAINT permissions_name_guard_name_unique UNIQUE (name, guard_name);

ALTER TABLE ONLY public.permissions
    ADD CONSTRAINT permissions_pkey PRIMARY KEY (id);

ALTER TABLE ONLY public.products
    ADD CONSTRAINT products_pkey PRIMARY KEY (id);

ALTER TABLE ONLY public.products
    ADD CONSTRAINT products_slug_unique UNIQUE (slug);

ALTER TABLE ONLY public.promociones
    ADD CONSTRAINT promociones_pkey PRIMARY KEY (id);

ALTER TABLE ONLY public.role_has_permissions
    ADD CONSTRAINT role_has_permissions_pkey PRIMARY KEY (permission_id, role_id);

ALTER TABLE ONLY public.roles
    ADD CONSTRAINT roles_name_guard_name_unique UNIQUE (name, guard_name);

ALTER TABLE ONLY public.roles
    ADD CONSTRAINT roles_pkey PRIMARY KEY (id);

ALTER TABLE ONLY public.sessions
    ADD CONSTRAINT sessions_pkey PRIMARY KEY (id);

ALTER TABLE ONLY public.settings
    ADD CONSTRAINT settings_key_unique UNIQUE (key);

ALTER TABLE ONLY public.settings
    ADD CONSTRAINT settings_pkey PRIMARY KEY (id);

ALTER TABLE ONLY public.users
    ADD CONSTRAINT users_email_unique UNIQUE (email);

ALTER TABLE ONLY public.users
    ADD CONSTRAINT users_pkey PRIMARY KEY (id);

CREATE INDEX cache_expiration_index ON public.cache USING btree (expiration);

CREATE INDEX cache_locks_expiration_index ON public.cache_locks USING btree (expiration);

CREATE INDEX categories_parent_id_index ON public.categories USING btree (parent_id);

CREATE INDEX failed_jobs_connection_queue_failed_at_index ON public.failed_jobs USING btree (connection, queue, failed_at);

CREATE INDEX jobs_queue_index ON public.jobs USING btree (queue);

CREATE INDEX model_has_permissions_model_id_model_type_index ON public.model_has_permissions USING btree (model_id, model_type);

CREATE INDEX model_has_roles_model_id_model_type_index ON public.model_has_roles USING btree (model_id, model_type);

CREATE INDEX order_items_order_id_index ON public.order_items USING btree (order_id);

CREATE INDEX order_items_product_id_index ON public.order_items USING btree (product_id);

CREATE INDEX orders_created_at_index ON public.orders USING btree (created_at);

CREATE INDEX orders_estado_index ON public.orders USING btree (estado);

CREATE INDEX orders_user_id_index ON public.orders USING btree (user_id);

CREATE INDEX passkeys_user_id_index ON public.passkeys USING btree (user_id);

CREATE INDEX products_activo_index ON public.products USING btree (activo);

CREATE INDEX products_category_id_index ON public.products USING btree (category_id);

CREATE INDEX products_stock_index ON public.products USING btree (stock);

CREATE INDEX promociones_fecha_fin_index ON public.promociones USING btree (fecha_fin);

CREATE INDEX promociones_fecha_inicio_index ON public.promociones USING btree (fecha_inicio);

CREATE INDEX sessions_last_activity_index ON public.sessions USING btree (last_activity);

CREATE INDEX sessions_user_id_index ON public.sessions USING btree (user_id);

ALTER TABLE ONLY public.categories
    ADD CONSTRAINT categories_parent_id_foreign FOREIGN KEY (parent_id) REFERENCES public.categories(id) ON DELETE SET NULL;

ALTER TABLE ONLY public.model_has_permissions
    ADD CONSTRAINT model_has_permissions_permission_id_foreign FOREIGN KEY (permission_id) REFERENCES public.permissions(id) ON DELETE CASCADE;

ALTER TABLE ONLY public.model_has_roles
    ADD CONSTRAINT model_has_roles_role_id_foreign FOREIGN KEY (role_id) REFERENCES public.roles(id) ON DELETE CASCADE;

ALTER TABLE ONLY public.order_items
    ADD CONSTRAINT order_items_order_id_foreign FOREIGN KEY (order_id) REFERENCES public.orders(id) ON DELETE CASCADE;

ALTER TABLE ONLY public.order_items
    ADD CONSTRAINT order_items_product_id_foreign FOREIGN KEY (product_id) REFERENCES public.products(id) ON DELETE CASCADE;

ALTER TABLE ONLY public.orders
    ADD CONSTRAINT orders_user_id_foreign FOREIGN KEY (user_id) REFERENCES public.users(id) ON DELETE CASCADE;

ALTER TABLE ONLY public.passkeys
    ADD CONSTRAINT passkeys_user_id_foreign FOREIGN KEY (user_id) REFERENCES public.users(id) ON DELETE CASCADE;

ALTER TABLE ONLY public.products
    ADD CONSTRAINT products_category_id_foreign FOREIGN KEY (category_id) REFERENCES public.categories(id) ON DELETE CASCADE;

ALTER TABLE ONLY public.promociones
    ADD CONSTRAINT promociones_product_id_foreign FOREIGN KEY (product_id) REFERENCES public.products(id) ON DELETE CASCADE;

ALTER TABLE ONLY public.role_has_permissions
    ADD CONSTRAINT role_has_permissions_permission_id_foreign FOREIGN KEY (permission_id) REFERENCES public.permissions(id) ON DELETE CASCADE;

ALTER TABLE ONLY public.role_has_permissions
    ADD CONSTRAINT role_has_permissions_role_id_foreign FOREIGN KEY (role_id) REFERENCES public.roles(id) ON DELETE CASCADE;

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

INSERT INTO public.categories VALUES (1, 'Gorras', 'gorras', 'Gorras de todos los estilos y marcas', NULL, NULL, true, '2026-06-25 08:17:26', '2026-06-25 08:17:26');
INSERT INTO public.categories VALUES (2, 'Accesorios', 'accesorios', 'Accesorios de moda para cualquier ocasión', NULL, NULL, true, '2026-06-25 08:17:26', '2026-06-25 08:17:26');
INSERT INTO public.categories VALUES (4, 'Ropa', 'ropa', 'Ropa casual y de tendencia', NULL, NULL, true, '2026-06-25 08:17:26', '2026-06-25 08:17:26');

INSERT INTO public.roles VALUES (1, 'gerente', 'web', '2026-06-25 08:17:25', '2026-06-25 08:17:25');
INSERT INTO public.roles VALUES (2, 'vendedor', 'web', '2026-06-25 08:17:25', '2026-06-25 08:17:25');
INSERT INTO public.roles VALUES (3, 'cliente', 'web', '2026-06-25 08:17:25', '2026-06-25 08:17:25');
INSERT INTO public.roles VALUES (4, 'admin_sistema', 'web', '2026-06-25 08:17:25', '2026-06-25 08:17:25');

INSERT INTO public.model_has_roles VALUES (4, 'App\Models\User', 1);
INSERT INTO public.model_has_roles VALUES (1, 'App\Models\User', 2);
INSERT INTO public.model_has_roles VALUES (2, 'App\Models\User', 3);
INSERT INTO public.model_has_roles VALUES (3, 'App\Models\User', 4);

INSERT INTO public.permissions VALUES (1, 'manage_catalog', 'web', '2026-06-25 08:17:25', '2026-06-25 08:17:25');
INSERT INTO public.permissions VALUES (2, 'manage_inventory', 'web', '2026-06-25 08:17:25', '2026-06-25 08:17:25');
INSERT INTO public.permissions VALUES (3, 'manage_orders', 'web', '2026-06-25 08:17:25', '2026-06-25 08:17:25');
INSERT INTO public.permissions VALUES (4, 'view_customers', 'web', '2026-06-25 08:17:25', '2026-06-25 08:17:25');
INSERT INTO public.permissions VALUES (5, 'manage_users', 'web', '2026-06-25 08:17:25', '2026-06-25 08:17:25');

INSERT INTO public.products VALUES (2, 'Pantalla', 'pantalla', 'Una pantalla de 90 pulgadas', NULL, 3, 5.00, 200.00, 15.00, 30, 5, NULL, true, '2026-06-25 08:17:26', '2026-06-25 08:17:26', NULL);
INSERT INTO public.products VALUES (1, 'Gorra Gallo', 'gorra-roja-gallo', 'Una hermosa gorra roja con bordado de gallo', 'productos/gorra-gallo-roja.webp', 1, 7.50, 15.00, 15.00, 18, 5, NULL, true, '2026-06-25 08:17:26', '2026-06-29 12:48:43', NULL);
INSERT INTO public.products VALUES (3, 'Gorra Gato', 'gorra-gato', 'Gorra con tematica de gato', 'productos/PBKk9c1AsNu57sGSNUyxgNkXDh5FJ7K4qvjU8fa1.webp', 1, 8.00, 20.00, 15.00, 3, 1, NULL, true, '2026-06-25 08:21:09', '2026-06-29 12:48:43', NULL);
INSERT INTO public.products VALUES (4, 'Gorra Tiburon', 'gorra-tiburon', 'Gorra con estampado de tiburon', 'productos/QukLZur0aZkhX1tajQlK0M8eKQTJzMWxwNoXEhRK.png', 1, 8.00, 14.00, 15.00, 50, 5, NULL, true, '2026-06-29 12:54:38', '2026-06-29 12:54:38', NULL);

INSERT INTO public.role_has_permissions VALUES (1, 1);
INSERT INTO public.role_has_permissions VALUES (2, 1);
INSERT INTO public.role_has_permissions VALUES (3, 2);
INSERT INTO public.role_has_permissions VALUES (4, 2);
INSERT INTO public.role_has_permissions VALUES (5, 4);

INSERT INTO public.users VALUES (1, 'Admin User', 'admin@example.com', NULL, '$2y$12$aGpFhfRiwnOZhOCzKhoEb.4XJLTJn9jtLx4kufPt1H.yOH32UXRpu', NULL, '2026-06-25 08:17:26', '2026-06-25 08:17:26', NULL, NULL, NULL, 'vendedor');
INSERT INTO public.users VALUES (2, 'Gerente User', 'gerente@example.com', NULL, '$2y$12$WSAWIqqgLCNwScRYEPgN0.ezA/EFgnHY/.TENvuWc7APTpnRW.x32', NULL, '2026-06-25 08:17:26', '2026-06-25 08:17:26', NULL, NULL, NULL, 'vendedor');
INSERT INTO public.users VALUES (3, 'Vendedor User', 'vendedor@example.com', NULL, '$2y$12$6W9aqkvzlmRX6SRVqi1l/OVLS5OILrXhUUOvIYZfJbCNdhsDp4yfm', NULL, '2026-06-25 08:17:26', '2026-06-25 08:17:26', NULL, NULL, NULL, 'vendedor');
INSERT INTO public.users VALUES (4, 'Cliente User', 'cliente@example.com', NULL, '$2y$12$tmJb/fhSVFdP3EAAkfkZ7uXemijnBis/K9SAt8W/06/hooxDWvYzm', NULL, '2026-06-25 08:17:26', '2026-06-25 08:17:26', NULL, NULL, NULL, 'vendedor');

SELECT pg_catalog.setval('public.categories_id_seq', 5, true);

SELECT pg_catalog.setval('public.permissions_id_seq', 5, true);

SELECT pg_catalog.setval('public.products_id_seq', 4, true);

SELECT pg_catalog.setval('public.roles_id_seq', 4, true);

SELECT pg_catalog.setval('public.users_id_seq', 4, true);

