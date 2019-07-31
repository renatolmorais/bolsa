--
-- PostgreSQL database dump
--

SET statement_timeout = 0;
SET lock_timeout = 0;
SET client_encoding = 'UTF8';
SET standard_conforming_strings = on;
SELECT pg_catalog.set_config('search_path', '', false);
SET check_function_bodies = false;
SET client_min_messages = warning;

--
-- Name: plpgsql; Type: EXTENSION; Schema: -; Owner: 
--

CREATE EXTENSION IF NOT EXISTS plpgsql WITH SCHEMA pg_catalog;


--
-- Name: EXTENSION plpgsql; Type: COMMENT; Schema: -; Owner: 
--

COMMENT ON EXTENSION plpgsql IS 'PL/pgSQL procedural language';


SET default_tablespace = '';

SET default_with_oids = false;

--
-- Name: api_busca; Type: TABLE; Schema: public; Owner: postgres; Tablespace: 
--

CREATE TABLE public.api_busca (
    id integer NOT NULL,
    token character varying NOT NULL,
    used character(1),
    created_at timestamp with time zone NOT NULL,
    used_at timestamp with time zone
);


ALTER TABLE public.api_busca OWNER TO postgres;

--
-- Name: api_busca_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.api_busca_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.api_busca_id_seq OWNER TO postgres;

--
-- Name: api_busca_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.api_busca_id_seq OWNED BY public.api_busca.id;


--
-- Name: operacoes; Type: TABLE; Schema: public; Owner: postgres; Tablespace: 
--

CREATE TABLE public.operacoes (
    id integer NOT NULL,
    data timestamp with time zone NOT NULL,
    operacao character varying(10) NOT NULL,
    codigo character varying(10) NOT NULL,
    quantidade integer NOT NULL,
    preco numeric NOT NULL,
    valor numeric NOT NULL,
    id_usuario integer NOT NULL
);


ALTER TABLE public.operacoes OWNER TO postgres;

--
-- Name: operacoes_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.operacoes_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.operacoes_id_seq OWNER TO postgres;

--
-- Name: operacoes_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.operacoes_id_seq OWNED BY public.operacoes.id;


--
-- Name: usuario; Type: TABLE; Schema: public; Owner: m26106; Tablespace: 
--

CREATE TABLE public.usuario (
    id integer NOT NULL,
    username character varying(100) NOT NULL,
    password character varying NOT NULL,
    salt character varying(100) DEFAULT '1qazxsw23edcvfr45tgbnh'::character varying NOT NULL,
    name character varying(500) NOT NULL
);


ALTER TABLE public.usuario OWNER TO m26106;

--
-- Name: usuario_id_seq; Type: SEQUENCE; Schema: public; Owner: m26106
--

CREATE SEQUENCE public.usuario_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.usuario_id_seq OWNER TO m26106;

--
-- Name: usuario_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: m26106
--

ALTER SEQUENCE public.usuario_id_seq OWNED BY public.usuario.id;


--
-- Name: id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.api_busca ALTER COLUMN id SET DEFAULT nextval('public.api_busca_id_seq'::regclass);


--
-- Name: id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.operacoes ALTER COLUMN id SET DEFAULT nextval('public.operacoes_id_seq'::regclass);


--
-- Name: id; Type: DEFAULT; Schema: public; Owner: m26106
--

ALTER TABLE ONLY public.usuario ALTER COLUMN id SET DEFAULT nextval('public.usuario_id_seq'::regclass);


--
-- Data for Name: api_busca; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.api_busca (id, token, used, created_at, used_at) FROM stdin;
3	fdf3ab27746c8fcdc5980f	N	2019-07-30 14:15:52-03	\N
4	bf547d835154f763e0bc6b	Y	2019-07-30 14:20:23-03	2019-07-30 14:20:26-03
5	4e3277ed2b1f0db4a619de	Y	2019-07-30 14:21:49-03	2019-07-30 14:21:52-03
6	487f9f0e72dd7d8921a0ea	Y	2019-07-30 14:21:53-03	2019-07-30 14:21:56-03
7	ae62f583256cf2d65337ae	N	2019-07-30 14:21:58-03	\N
8	2710c46693606672323240	Y	2019-07-30 14:22:01-03	2019-07-30 14:22:04-03
9	4536851fa97bf1729e83d2	Y	2019-07-30 14:22:06-03	2019-07-30 14:22:46-03
10	1c57ab6e9d863e6102bb58	Y	2019-07-30 14:22:48-03	2019-07-30 14:22:52-03
11	f348b01d87c3c35b45f180	Y	2019-07-31 09:20:26-03	2019-07-31 09:20:28-03
12	d5258418ac415a7b07c394	Y	2019-07-31 09:20:30-03	2019-07-31 09:21:06-03
13	5679a32e32b2cc37e7b167	N	2019-07-31 09:21:08-03	\N
14	d1e42e9f2a686a13f395cc	N	2019-07-31 09:21:15-03	\N
15	0ce8e9eb3f1ad2b4ce376d	Y	2019-07-31 09:21:30-03	2019-07-31 09:21:32-03
16	6121d4ae7c521cde28d2de	N	2019-07-31 09:21:36-03	\N
17	62499885c88b4bf6c0ab46	Y	2019-07-31 09:53:11-03	2019-07-31 09:53:22-03
18	3b65e5ff383496077fb6e7	N	2019-07-31 09:53:24-03	\N
19	4e2cec1cdc2d4b0e820456	N	2019-07-31 09:57:11-03	\N
20	7ef9ef810a20791aeb83a4	Y	2019-07-31 09:57:15-03	2019-07-31 09:57:16-03
21	0d374cb946fd22358ffb39	Y	2019-07-31 09:57:19-03	2019-07-31 09:57:21-03
22	3754e6bdd62a0bfefa7e3d	N	2019-07-31 09:58:00-03	\N
23	70b7c7975b211fe5f0007f	N	2019-07-31 10:24:17-03	\N
24	cce2a1431a1d68b166b3eb	N	2019-07-31 12:54:50-03	\N
25	e46210a117f166d3028afc	N	2019-07-31 12:56:13-03	\N
26	2899c2ce27b49a2ffe103b	N	2019-07-31 12:56:55-03	\N
27	009d1d75a8410dc1ad3da1	Y	2019-07-31 12:57:11-03	2019-07-31 12:57:13-03
28	af037eb39f3b07ad39767e	N	2019-07-31 12:57:15-03	\N
\.


--
-- Name: api_busca_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.api_busca_id_seq', 28, true);


--
-- Data for Name: operacoes; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.operacoes (id, data, operacao, codigo, quantidade, preco, valor, id_usuario) FROM stdin;
5	2019-07-08 00:00:00-03	1	ROMI3	100	25	2500	1
6	2018-07-07 00:00:00-03	2	ITSA4	100	9.75	975	1
1	2019-07-31 00:00:00-03	1	BIDI4	100	18.00	1800.00	1
2	2019-07-29 00:00:00-03	1	MOVI3	100	18.00	1800.00	1
4	2019-01-02 00:00:00-03	1	STBP3	100	5.00	500.00	5
\.


--
-- Name: operacoes_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.operacoes_id_seq', 6, true);


--
-- Data for Name: usuario; Type: TABLE DATA; Schema: public; Owner: m26106
--

COPY public.usuario (id, username, password, salt, name) FROM stdin;
1	renato	$2y$10$1qazxsw23edcvfr45tgbnelTjnHMp/6krXHB0nFJ.Wbl7u9hES2Ma	1qazxsw23edcvfr45tgbnh	Renato Lopes de Morais
5	anapaula	$2y$10$36b6b9d6401dd98f2e798uSYhR5ouKKMOG1ZLdRqlsf4ONY2BFurK	36b6b9d6401dd98f2e7982	Ana Paula Prates Diniz Paiva
12	sonia	$2y$10$b60634ef9bbd6c14bded6uBDwooh/2bwjI8nwAEnpMFfXvH3sP.1u	b60634ef9bbd6c14bded61	sonia
14	edson	$2y$10$88438d15717f64c21c102uejc10EgWPMSCoOtiIvcnrYfQtUflg6y	88438d15717f64c21c1021	edson
16	thais	$2y$10$259cbe450ac29df2dd62duxRZN8DfSmgJZc//7rP1JWBlHZf8Tkue	259cbe450ac29df2dd62d7	thais
17	john.doe	$2y$10$a6b6470d77f1762883fb7OLM5.HJsZguQyu0EO4JEtJVG70adsCx.	a6b6470d77f1762883fb7b	John Doe
\.


--
-- Name: usuario_id_seq; Type: SEQUENCE SET; Schema: public; Owner: m26106
--

SELECT pg_catalog.setval('public.usuario_id_seq', 17, true);


--
-- Name: api_busca_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres; Tablespace: 
--

ALTER TABLE ONLY public.api_busca
    ADD CONSTRAINT api_busca_pkey PRIMARY KEY (id);


--
-- Name: id_pkey; Type: CONSTRAINT; Schema: public; Owner: m26106; Tablespace: 
--

ALTER TABLE ONLY public.usuario
    ADD CONSTRAINT id_pkey PRIMARY KEY (id);


--
-- Name: operacoes_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres; Tablespace: 
--

ALTER TABLE ONLY public.operacoes
    ADD CONSTRAINT operacoes_pkey PRIMARY KEY (id);


--
-- Name: usuario_fkey; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.operacoes
    ADD CONSTRAINT usuario_fkey FOREIGN KEY (id_usuario) REFERENCES public.usuario(id);


--
-- Name: SCHEMA public; Type: ACL; Schema: -; Owner: postgres
--

REVOKE ALL ON SCHEMA public FROM PUBLIC;
REVOKE ALL ON SCHEMA public FROM postgres;
GRANT ALL ON SCHEMA public TO postgres;
GRANT ALL ON SCHEMA public TO PUBLIC;


--
-- PostgreSQL database dump complete
--

