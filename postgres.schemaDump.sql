--
-- PostgreSQL database dump
--

-- Dumped from database version 9.6.3
-- Dumped by pg_dump version 9.6.3

SET statement_timeout = 0;
SET lock_timeout = 0;
SET idle_in_transaction_session_timeout = 0;
SET client_encoding = 'UTF8';
SET standard_conforming_strings = on;
SET check_function_bodies = false;
SET client_min_messages = warning;
SET row_security = off;

--
-- Name: plpgsql; Type: EXTENSION; Schema: -; Owner: 
--

CREATE EXTENSION IF NOT EXISTS plpgsql WITH SCHEMA pg_catalog;


--
-- Name: EXTENSION plpgsql; Type: COMMENT; Schema: -; Owner: 
--

COMMENT ON EXTENSION plpgsql IS 'PL/pgSQL procedural language';


SET search_path = public, pg_catalog;

SET default_tablespace = '';

SET default_with_oids = false;

--
-- Name: admins; Type: TABLE; Schema: public; Owner: spaghettify
--

CREATE TABLE admins (
    id bigint NOT NULL,
    name character varying(100) NOT NULL,
    username character varying(20) NOT NULL,
    password_hash character varying(255) NOT NULL,
    role_id integer NOT NULL,
    created timestamp without time zone NOT NULL
);


ALTER TABLE admins OWNER TO spaghettify;

--
-- Name: adminis_id_seq; Type: SEQUENCE; Schema: public; Owner: spaghettify
--

CREATE SEQUENCE adminis_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE adminis_id_seq OWNER TO spaghettify;

--
-- Name: adminis_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: spaghettify
--

ALTER SEQUENCE adminis_id_seq OWNED BY admins.id;


--
-- Name: roles; Type: TABLE; Schema: public; Owner: spaghettify
--

CREATE TABLE roles (
    id integer NOT NULL,
    role character varying(100) NOT NULL,
    created timestamp without time zone NOT NULL
);


ALTER TABLE roles OWNER TO spaghettify;

--
-- Name: roles_id_seq; Type: SEQUENCE; Schema: public; Owner: spaghettify
--

CREATE SEQUENCE roles_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE roles_id_seq OWNER TO spaghettify;

--
-- Name: roles_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: spaghettify
--

ALTER SEQUENCE roles_id_seq OWNED BY roles.id;


--
-- Name: testimonials; Type: TABLE; Schema: public; Owner: spaghettify
--

CREATE TABLE testimonials (
    testimonial_id bigint NOT NULL,
    testimonial_text text NOT NULL,
    person character varying(50) NOT NULL,
    place character varying(100) NOT NULL,
    active boolean DEFAULT true NOT NULL,
    created timestamp without time zone NOT NULL
);


ALTER TABLE testimonials OWNER TO spaghettify;

--
-- Name: testimonials_testimonial_id_seq; Type: SEQUENCE; Schema: public; Owner: spaghettify
--

CREATE SEQUENCE testimonials_testimonial_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE testimonials_testimonial_id_seq OWNER TO spaghettify;

--
-- Name: testimonials_testimonial_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: spaghettify
--

ALTER SEQUENCE testimonials_testimonial_id_seq OWNED BY testimonials.testimonial_id;


--
-- Name: admins id; Type: DEFAULT; Schema: public; Owner: spaghettify
--

ALTER TABLE ONLY admins ALTER COLUMN id SET DEFAULT nextval('adminis_id_seq'::regclass);


--
-- Name: roles id; Type: DEFAULT; Schema: public; Owner: spaghettify
--

ALTER TABLE ONLY roles ALTER COLUMN id SET DEFAULT nextval('roles_id_seq'::regclass);


--
-- Name: testimonials testimonial_id; Type: DEFAULT; Schema: public; Owner: spaghettify
--

ALTER TABLE ONLY testimonials ALTER COLUMN testimonial_id SET DEFAULT nextval('testimonials_testimonial_id_seq'::regclass);


--
-- Name: adminis_id_seq; Type: SEQUENCE SET; Schema: public; Owner: spaghettify
--

SELECT pg_catalog.setval('adminis_id_seq', 1, true);


--
-- Data for Name: admins; Type: TABLE DATA; Schema: public; Owner: spaghettify
--

COPY admins (id, name, username, password_hash, role_id, created) FROM stdin;
1	Business Owner	chief	$2y$10$VsqnRxezbSuJMOfuL1Ks4uSWreetmlzfquXh5TgubMnDAAheW8wbm	1	2017-07-11 07:50:05.588499
\.


--
-- Data for Name: roles; Type: TABLE DATA; Schema: public; Owner: spaghettify
--

COPY roles (id, role, created) FROM stdin;
1	owner	2017-07-11 07:47:54.556983
\.


--
-- Name: roles_id_seq; Type: SEQUENCE SET; Schema: public; Owner: spaghettify
--

SELECT pg_catalog.setval('roles_id_seq', 1, true);


--
-- Data for Name: testimonials; Type: TABLE DATA; Schema: public; Owner: spaghettify
--

COPY testimonials (testimonial_id, testimonial_text, person, place, active, created) FROM stdin;
\.


--
-- Name: testimonials_testimonial_id_seq; Type: SEQUENCE SET; Schema: public; Owner: spaghettify
--

SELECT pg_catalog.setval('testimonials_testimonial_id_seq', 1, false);


--
-- Name: admins adminis_pkey; Type: CONSTRAINT; Schema: public; Owner: spaghettify
--

ALTER TABLE ONLY admins
    ADD CONSTRAINT adminis_pkey PRIMARY KEY (id);


--
-- Name: admins admins_username_key; Type: CONSTRAINT; Schema: public; Owner: spaghettify
--

ALTER TABLE ONLY admins
    ADD CONSTRAINT admins_username_key UNIQUE (username);


--
-- Name: roles roles_pkey; Type: CONSTRAINT; Schema: public; Owner: spaghettify
--

ALTER TABLE ONLY roles
    ADD CONSTRAINT roles_pkey PRIMARY KEY (id);


--
-- Name: roles roles_role_key; Type: CONSTRAINT; Schema: public; Owner: spaghettify
--

ALTER TABLE ONLY roles
    ADD CONSTRAINT roles_role_key UNIQUE (role);


--
-- Name: testimonials testimonials_pkey; Type: CONSTRAINT; Schema: public; Owner: spaghettify
--

ALTER TABLE ONLY testimonials
    ADD CONSTRAINT testimonials_pkey PRIMARY KEY (testimonial_id);


--
-- PostgreSQL database dump complete
--

