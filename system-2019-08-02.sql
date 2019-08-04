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
-- Name: carteira; Type: TABLE; Schema: public; Owner: postgres; Tablespace: 
--

CREATE TABLE public.carteira (
    id integer NOT NULL,
    id_usuario integer NOT NULL,
    codigo character varying(10) NOT NULL,
    quantidade integer NOT NULL
);


ALTER TABLE public.carteira OWNER TO postgres;

--
-- Name: carteira_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.carteira_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.carteira_id_seq OWNER TO postgres;

--
-- Name: carteira_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.carteira_id_seq OWNED BY public.carteira.id;


--
-- Name: operacoes; Type: TABLE; Schema: public; Owner: postgres; Tablespace: 
--

CREATE TABLE public.operacoes (
    id integer NOT NULL,
    data timestamp without time zone NOT NULL,
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

ALTER TABLE ONLY public.carteira ALTER COLUMN id SET DEFAULT nextval('public.carteira_id_seq'::regclass);


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
4	bf547d835154f763e0bc6b	Y	2019-07-30 14:20:23-03	2019-07-30 14:20:26-03
5	4e3277ed2b1f0db4a619de	Y	2019-07-30 14:21:49-03	2019-07-30 14:21:52-03
6	487f9f0e72dd7d8921a0ea	Y	2019-07-30 14:21:53-03	2019-07-30 14:21:56-03
8	2710c46693606672323240	Y	2019-07-30 14:22:01-03	2019-07-30 14:22:04-03
9	4536851fa97bf1729e83d2	Y	2019-07-30 14:22:06-03	2019-07-30 14:22:46-03
10	1c57ab6e9d863e6102bb58	Y	2019-07-30 14:22:48-03	2019-07-30 14:22:52-03
11	f348b01d87c3c35b45f180	Y	2019-07-31 09:20:26-03	2019-07-31 09:20:28-03
12	d5258418ac415a7b07c394	Y	2019-07-31 09:20:30-03	2019-07-31 09:21:06-03
15	0ce8e9eb3f1ad2b4ce376d	Y	2019-07-31 09:21:30-03	2019-07-31 09:21:32-03
17	62499885c88b4bf6c0ab46	Y	2019-07-31 09:53:11-03	2019-07-31 09:53:22-03
20	7ef9ef810a20791aeb83a4	Y	2019-07-31 09:57:15-03	2019-07-31 09:57:16-03
21	0d374cb946fd22358ffb39	Y	2019-07-31 09:57:19-03	2019-07-31 09:57:21-03
27	009d1d75a8410dc1ad3da1	Y	2019-07-31 12:57:11-03	2019-07-31 12:57:13-03
31	1b5517a9eafce8c36add09	Y	2019-08-01 08:49:48-03	2019-08-01 08:49:50-03
33	d22beeb851979a86b6e40f	Y	2019-08-01 08:54:58-03	2019-08-01 08:55:00-03
35	1d169080613d05fdae6886	Y	2019-08-01 08:59:25-03	2019-08-01 08:59:27-03
3	fdf3ab27746c8fcdc5980f	Y	2019-07-30 14:15:52-03	2019-08-01 09:48:11-03
7	ae62f583256cf2d65337ae	Y	2019-07-30 14:21:58-03	2019-08-01 09:49:30-03
13	5679a32e32b2cc37e7b167	Y	2019-07-31 09:21:08-03	2019-08-01 09:49:30-03
14	d1e42e9f2a686a13f395cc	Y	2019-07-31 09:21:15-03	2019-08-01 09:49:30-03
16	6121d4ae7c521cde28d2de	Y	2019-07-31 09:21:36-03	2019-08-01 09:49:30-03
18	3b65e5ff383496077fb6e7	Y	2019-07-31 09:53:24-03	2019-08-01 09:49:30-03
19	4e2cec1cdc2d4b0e820456	Y	2019-07-31 09:57:11-03	2019-08-01 09:49:30-03
22	3754e6bdd62a0bfefa7e3d	Y	2019-07-31 09:58:00-03	2019-08-01 09:49:30-03
23	70b7c7975b211fe5f0007f	Y	2019-07-31 10:24:17-03	2019-08-01 09:49:30-03
24	cce2a1431a1d68b166b3eb	Y	2019-07-31 12:54:50-03	2019-08-01 09:49:30-03
25	e46210a117f166d3028afc	Y	2019-07-31 12:56:13-03	2019-08-01 09:49:30-03
26	2899c2ce27b49a2ffe103b	Y	2019-07-31 12:56:55-03	2019-08-01 09:49:30-03
28	af037eb39f3b07ad39767e	Y	2019-07-31 12:57:15-03	2019-08-01 09:49:30-03
29	db758fde316ca3036692f6	Y	2019-08-01 08:48:50-03	2019-08-01 09:49:30-03
30	4b794ebbf00de0929cac63	Y	2019-08-01 08:49:01-03	2019-08-01 09:49:30-03
32	5264170eb9f8801d2bc4c9	Y	2019-08-01 08:49:52-03	2019-08-01 09:49:30-03
34	97045755e361a5e6d50497	Y	2019-08-01 08:55:02-03	2019-08-01 09:49:30-03
36	55994913d2d38b5005490c	Y	2019-08-01 09:06:13-03	2019-08-01 09:49:30-03
37	6413a71b8e94f08513b561	Y	2019-08-01 09:28:17-03	2019-08-01 09:49:30-03
38	6a6bf2e337c48b22e0cae0	Y	2019-08-02 11:12:33-03	2019-08-02 11:12:37-03
\.


--
-- Name: api_busca_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.api_busca_id_seq', 38, true);


--
-- Data for Name: carteira; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.carteira (id, id_usuario, codigo, quantidade) FROM stdin;
1	1	BIDI4	1000
\.


--
-- Name: carteira_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.carteira_id_seq', 1, true);


--
-- Data for Name: operacoes; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.operacoes (id, data, operacao, codigo, quantidade, preco, valor, id_usuario) FROM stdin;
271	2018-09-14 00:00:00	1	CMIG4	100	6.75	675.0	1
272	2018-09-14 00:00:00	1	GGBR4	200	15.2	3040.0	1
273	2018-09-14 00:00:00	1	MGLU3F	1	115.42	115.42	1
278	2018-09-19 00:00:00	1	BIDI4	100	28.5	2850.0	1
279	2018-09-19 00:00:00	1	BIDI4	95	28.4	2698.0	1
282	2018-09-24 00:00:00	1	MGLU3F	13	121.14	1574.82	1
283	2018-09-24 00:00:00	1	MGLU3F	10	121.69	1216.9	1
284	2018-09-24 00:00:00	1	ITSA4	100	9.59	959.0	1
285	2018-09-25 00:00:00	1	ITSA4	200	9.52	1904.0	1
288	2018-10-01 00:00:00	1	CMIG4	100	7.0	700.0	1
291	2018-10-05 00:00:00	1	GGBR4	100	15.9	1590.0	1
292	2018-10-05 00:00:00	1	GGBR4	100	15.85	1585.0	1
293	2018-10-05 00:00:00	1	GGBR4	100	15.79	1579.0	1
294	2018-10-05 00:00:00	1	GGBR4	100	15.7	1570.0	1
295	2018-10-08 00:00:00	1	GGBR4	100	15.7	1570.0	1
297	2018-10-16 00:00:00	1	FJTA4	200	6.1	1220.0	1
302	2018-10-17 00:00:00	1	FJTA4	100	6.0	600.0	1
303	2018-10-17 00:00:00	1	FJTA4	100	5.9	590.0	1
304	2018-10-17 00:00:00	1	GGBR4	100	15.8	1580.0	1
305	2018-10-17 00:00:00	1	CCRO3	100	10.3	1030.0	1
306	2018-10-17 00:00:00	1	CCRO3	100	10.29	1029.0	1
307	2018-10-17 00:00:00	1	ITSA4	100	11.1	1110.0	1
308	2018-10-18 00:00:00	1	TRIS3	300	2.69	807.0	1
309	2018-10-18 00:00:00	1	TRIS3	36	2.68	96.48	1
311	2018-10-18 00:00:00	1	ITSA4	100	10.9	1090.0	1
312	2018-10-18 00:00:00	1	GGBR4	100	15.5	1550.0	1
313	2018-10-18 00:00:00	1	TRIS3	50	2.69	134.5	1
315	2018-10-19 00:00:00	1	FJTA4	100	11.0	1100.0	1
316	2018-10-19 00:00:00	1	FJTA4	100	9.2	920.0	1
317	2018-10-24 00:00:00	1	CCRO3	14	10.25	143.5	1
319	2018-10-29 00:00:00	1	FJTA4	300	9.82	2946.0	1
320	2018-10-29 00:00:00	1	BIDI4	1	0.0	0.0	1
322	2018-11-01 00:00:00	1	USIM5	200	10.15	2030.0	1
324	2018-11-06 00:00:00	1	TRIS3	14	2.91	40.74	1
325	2018-11-06 00:00:00	1	TIET11	100	10.73	1073.0	1
328	2018-11-13 00:00:00	1	GGBR4	200	14.92	2984.0	1
329	2018-11-13 00:00:00	1	GGBR4	15	14.9	223.5	1
331	2018-11-19 00:00:00	1	MRVE3	100	12.38	1238.0	1
334	2018-11-26 00:00:00	1	ROMI3	100	8.29	829.0	1
335	2018-11-26 00:00:00	1	TRIS3	100	3.23	323.0	1
336	2018-11-27 00:00:00	1	VVAR3	100	5.0	500.0	1
337	2018-11-27 00:00:00	1	VVAR3	100	4.98	498.0	1
338	2018-11-27 00:00:00	1	VVAR3	100	4.95	495.0	1
340	2018-11-28 00:00:00	1	POMO4	200	4.13	826.0	1
341	2018-11-28 00:00:00	1	VVAR3	100	4.9	490.0	1
342	2018-12-03 00:00:00	1	TCSA3	200	1.3	260.0	1
345	2018-12-04 00:00:00	1	MRVE3	100	11.77	1177.0	1
346	2018-12-04 00:00:00	1	TCSA3	200	1.26	252.0	1
347	2018-12-04 00:00:00	1	USIM5	100	9.38	938.0	1
349	2018-12-11 00:00:00	1	USIM5	100	8.64	864.0	1
353	2018-12-14 00:00:00	1	MRVE3	200	11.78	2356.0	1
354	2018-12-14 00:00:00	1	VVAR3	100	4.55	455.0	1
355	2018-12-17 00:00:00	1	TCSA3	100	1.5	150.0	1
357	2018-12-17 00:00:00	1	VVAR3	200	4.37	874.0	1
358	2018-12-17 00:00:00	1	TCSA3	100	1.47	147.0	1
360	2018-12-19 00:00:00	1	VVAR3	200	4.68	936.0	1
361	2018-12-19 00:00:00	1	TCSA3	200	1.47	294.0	1
363	2018-12-20 00:00:00	1	USIM5	100	9.42	942.0	1
366	2018-12-20 00:00:00	1	USIM5	300	9.29	2787.0	1
367	2018-12-20 00:00:00	1	GGBR4	100	14.83	1483.0	1
368	2018-12-21 00:00:00	1	TCSA3	200	1.38	276.0	1
369	2018-12-22 00:00:00	1	TCSA3	200	1.37	274.0	1
370	2018-12-23 00:00:00	1	TCSA3	200	1.36	272.0	1
372	2019-01-02 00:00:00	1	VVAR3	300	4.37	1311.0	1
376	2019-01-02 00:00:00	1	MOVI3	100	8.79	879.0	1
377	2019-01-02 00:00:00	1	STBP3	100	4.18	418.0	1
378	2019-01-02 00:00:00	1	TPIS3	300	1.6	480.0	1
379	2019-01-02 00:00:00	1	VVAR3	100	4.41	441.0	1
380	2019-01-02 00:00:00	1	POMO4	200	4.21	842.0	1
381	2019-01-03 00:00:00	1	VVAR3	100	4.38	438.0	1
382	2019-01-03 00:00:00	1	TPIS3	200	1.66	332.0	1
384	2019-01-03 00:00:00	1	VVAR3	200	4.31	862.0	1
386	2019-01-04 00:00:00	1	VVAR3	200	4.34	868.0	1
387	2019-01-07 00:00:00	1	VVAR3	100	4.29	429.0	1
388	2019-01-07 00:00:00	1	TAEE4	100	7.91	791.0	1
390	2019-01-09 00:00:00	1	TAEE4	200	7.9	1580.0	1
391	2019-01-09 00:00:00	1	JHSF3	100	1.93	193.0	1
392	2019-01-09 00:00:00	1	PDGR3	20	10.06	201.2	1
393	2019-01-10 00:00:00	1	TAEE4	100	7.8	780.0	1
394	2019-01-10 00:00:00	1	STBP3	100	4.22	422.0	1
395	2019-01-10 00:00:00	1	POMO4	200	4.15	830.0	1
396	2019-01-11 00:00:00	1	POMO4	200	4.11	822.0	1
397	2019-01-11 00:00:00	1	VIVR3	200	0.44	88.0	1
400	2019-01-14 00:00:00	1	KROT3	200	9.84	1968.0	1
402	2019-01-14 00:00:00	1	MOVI3	100	9.43	943.0	1
403	2019-01-14 00:00:00	1	KROT3	200	10.01	2002.0	1
404	2019-01-15 00:00:00	1	VIVR3	600	0.42	252.0	1
408	2019-01-24 00:00:00	1	TCSA3	1300	1.37	1781.0	1
410	2019-01-24 00:00:00	1	GGBR4	100	15.74	1574.0	1
411	2019-01-24 00:00:00	1	JHSF3	100	1.9	190.0	1
412	2019-02-11 00:00:00	1	POMO4	100	3.92	392.0	1
413	2019-02-11 00:00:00	1	POMO4	100	3.89	389.0	1
414	2019-02-25 00:00:00	1	JHSF3	45	2.2	99.0	1
416	2019-03-01 00:00:00	1	GGBR4	100	15.11	1511.0	1
239	2018-05-23 00:00:00	2	BIDI4	100	18.6	1860.0	1
242	2018-08-15 00:00:00	2	BIDI4	160	22.0	3520.0	1
246	2018-08-22 00:00:00	2	ITSA4	300	9.75	2925.0	1
252	2018-08-29 00:00:00	2	GGBR4	100	16.5	1650.0	1
254	2018-08-29 00:00:00	2	ITSA4	100	9.8	980.0	1
255	2018-08-29 00:00:00	2	ITSA4	100	9.86	986.0	1
256	2018-08-29 00:00:00	2	ITSA4	100	9.85	985.0	1
260	2018-09-06 00:00:00	2	ITSA4	100	9.7	970.0	1
261	2018-09-06 00:00:00	2	GGBR4	200	15.98	3196.0	1
262	2018-09-07 00:00:00	2	BIDI4	4	30.5	122.0	1
264	2018-09-11 00:00:00	2	PETR4	200	18.75	3750.0	1
268	2018-09-14 00:00:00	2	GGBR4	300	15.45	4635.0	1
269	2018-09-14 00:00:00	2	ITSA4	100	9.5	950.0	1
274	2018-09-17 00:00:00	2	GGBR4	100	15.6	1560.0	1
275	2018-09-17 00:00:00	2	GGBR4	100	15.7	1570.0	1
276	2018-09-17 00:00:00	2	CMIG4	100	6.95	695.0	1
277	2018-09-17 00:00:00	2	CMIG4	100	6.95	695.0	1
280	2018-09-24 00:00:00	2	BIDI4	100	29.7	2970.0	1
281	2018-09-24 00:00:00	2	BIDI4	95	29.7	2821.5	1
286	2018-09-27 00:00:00	2	ITSA4	300	10.0	3000.0	1
287	2018-09-27 00:00:00	2	MGLU3F	35	126.0	4410.0	1
289	2018-10-03 00:00:00	2	MGLU3F	1	138.52	138.52	1
290	2018-10-04 00:00:00	2	CMIG4	100	7.83	783.0	1
296	2018-10-16 00:00:00	2	GGBR4	100	16.0	1600.0	1
298	2018-10-16 00:00:00	2	GGBR4	100	16.05	1605.0	1
299	2018-10-16 00:00:00	2	GGBR4	100	16.1	1610.0	1
300	2018-10-16 00:00:00	2	GGBR4	100	16.15	1615.0	1
301	2018-10-16 00:00:00	2	GGBR4	100	16.2	1620.0	1
310	2018-10-18 00:00:00	2	FJTA4	400	9.05	3620.0	1
314	2018-10-19 00:00:00	2	TRIS3	100	2.71	271.0	1
318	2018-10-29 00:00:00	2	GGBR4	200	15.8	3160.0	1
321	2018-10-31 00:00:00	2	FJTA4	500	4.37	2185.0	1
323	2018-11-06 00:00:00	2	CCRO3	14	11.15	156.1	1
326	2018-11-13 00:00:00	2	TRIS3	300	3.01	903.0	1
327	2018-11-13 00:00:00	2	ITSA4	200	11.34	2268.0	1
330	2018-11-16 00:00:00	2	GGBR4	100	16.0	1600.0	1
332	2018-11-23 00:00:00	2	GGBR4	115	14.92	1715.8	1
333	2018-11-23 00:00:00	2	CCRO3	100	11.4	1140.0	1
339	2018-11-28 00:00:00	2	CCRO3	100	11.29	1129.0	1
343	2018-12-04 00:00:00	2	TRIS3	100	3.29	329.0	1
344	2018-12-04 00:00:00	2	VVAR3	400	5.09	2036.0	1
348	2018-12-10 00:00:00	2	ROMI3	100	8.4	840.0	1
350	2018-12-14 00:00:00	2	TCSA3	200	1.45	290.0	1
351	2018-12-14 00:00:00	2	POMO4	200	4.15	830.0	1
352	2018-12-14 00:00:00	2	USIM5	200	9.6	1920.0	1
356	2018-12-17 00:00:00	2	TIET11	100	10.06	1006.0	1
359	2018-12-18 00:00:00	2	MRVE3	100	12.08	1208.0	1
362	2018-12-20 00:00:00	2	MRVE3	100	12.41	1241.0	1
364	2018-12-20 00:00:00	2	MRVE3	200	12.35	2470.0	1
365	2018-12-20 00:00:00	2	VVAR3	500	4.65	2325.0	1
371	2019-01-02 00:00:00	2	GGBR4	100	14.95	1495.0	1
373	2019-01-02 00:00:00	2	USIM5	100	9.52	952.0	1
374	2019-01-02 00:00:00	2	USIM5	100	9.7	970.0	1
375	2019-01-02 00:00:00	2	USIM5	100	9.8	980.0	1
383	2019-01-03 00:00:00	2	USIM5	100	9.95	995.0	1
385	2019-01-04 00:00:00	2	USIM5	200	10.05	2010.0	1
389	2019-01-09 00:00:00	2	VVAR3	1000	3.92	3920.0	1
398	2019-01-11 00:00:00	2	TAEE4	400	7.81	3124.0	1
399	2019-01-11 00:00:00	2	TPIS3	500	1.8	900.0	1
401	2019-01-14 00:00:00	2	KROT3	200	9.86	1972.0	1
405	2019-01-17 00:00:00	2	PDGR3	20	9.0	180.0	1
406	2019-01-22 00:00:00	2	JHSF3	100	1.9	190.0	1
407	2019-01-23 00:00:00	2	TCSA3	1200	1.39	1668.0	1
409	2019-01-24 00:00:00	2	TCSA3	1300	1.33	1729.0	1
415	2019-03-01 00:00:00	2	POMO4	400	4.12	1648.0	1
237	2018-04-30 00:00:00	1	BIDI4	160	18.5	2960.0	1
238	2018-05-03 00:00:00	1	BIDI4	4	18.71	74.85	1
240	2018-05-25 00:00:00	1	BIDI4	100	18.3	1830.0	1
241	2018-08-15 00:00:00	1	BIDI10	1	11.94	11.94	1
243	2018-08-21 00:00:00	1	GGBR4	100	16.0	1600.0	1
244	2018-08-21 00:00:00	1	ITSA4	300	9.5	2850.0	1
245	2018-08-21 00:00:00	1	MGLU3F	4	131.29	525.16	1
247	2018-08-23 00:00:00	1	GGBR4	100	15.8	1580.0	1
248	2018-08-23 00:00:00	1	ITSA4	100	9.35	935.0	1
249	2018-08-23 00:00:00	1	ITSA4	100	9.37	937.0	1
250	2018-08-23 00:00:00	1	ITSA4	100	9.4	940.0	1
251	2018-08-24 00:00:00	1	MGLU3F	4	131.79	527.16	1
253	2018-08-29 00:00:00	1	ITSA4	100	9.86	986.0	1
257	2018-08-30 00:00:00	1	GGBR4	100	15.87	1587.0	1
258	2018-08-30 00:00:00	1	GGBR4	100	15.9	1590.0	1
259	2018-09-03 00:00:00	1	MGLU3F	3	128.2	384.6	1
263	2018-09-10 00:00:00	1	PETR4	200	19.34	3868.0	1
265	2018-09-11 00:00:00	1	GGBR4	200	15.05	3010.0	1
266	2018-09-11 00:00:00	1	ITSA4	100	9.32	932.0	1
267	2018-09-12 00:00:00	1	MGLU3F	1	120.0	120.0	1
270	2018-09-14 00:00:00	1	CMIG4	100	6.75	675.0	1
417	2019-03-01 00:00:00	1	VIVR3	7	0.38	2.66	1
419	2019-03-07 00:00:00	1	GGBR4	100	14.8	1480.0	1
420	2019-03-07 00:00:00	1	STBP3	100	3.62	362.0	1
421	2019-03-07 00:00:00	1	STBP3	300	3.6	1080.0	1
422	2019-03-07 00:00:00	1	STBP3	99	3.6	356.4	1
424	2019-03-12 00:00:00	1	STBP3	121	3.56	430.76	1
428	2019-03-15 00:00:00	1	GGBR4	100	14.93	1493.0	1
429	2019-03-15 00:00:00	1	STBP3	200	3.6	720.0	1
430	2019-03-15 00:00:00	1	STBP3	50	3.6	180.0	1
431	2019-03-18 00:00:00	1	POMO4	100	4.07	407.0	1
435	2019-03-20 00:00:00	1	ROMI3	200	11.19	2238.0	1
436	2019-03-20 00:00:00	1	ROMI3	200	11.15	2230.0	1
438	2019-03-20 00:00:00	1	ROMI3	200	11.06	2212.0	1
439	2019-03-27 00:00:00	1	JSLG3	200	9.66	1932.0	1
440	2019-03-27 00:00:00	1	STBP3	200	3.66	732.0	1
441	2019-03-27 00:00:00	1	TCSA3	100	1.28	128.0	1
443	2019-04-02 00:00:00	1	STBP3	400	3.67	1468.0	1
444	2019-04-02 00:00:00	1	POMO4	100	3.88	388.0	1
445	2019-04-02 00:00:00	1	USIM5	200	9.7	1940.0	1
446	2019-04-03 00:00:00	1	POMO4	85	3.86	328.1	1
448	2019-04-04 00:00:00	1	POMO4	200	3.85	770.0	1
449	2019-04-04 00:00:00	1	USIM5	100	9.7	970.0	1
450	2019-04-04 00:00:00	1	POMO4	15	3.85	57.75	1
451	2019-04-04 00:00:00	1	STBP3	30	3.84	115.2	1
452	2019-04-09 00:00:00	1	USIM5	100	9.44	944.0	1
453	2019-04-12 00:00:00	1	TCSA3	100	1.29	129.0	1
454	2019-04-12 00:00:00	1	TCSA3	59	1.3	76.7	1
456	2019-05-16 00:00:00	1	STBP3	100	3.69	369.0	1
457	2019-05-16 00:00:00	1	POMO4	100	3.32	332.0	1
458	2019-05-16 00:00:00	1	USIM5	200	7.9	1580.0	1
459	2019-05-16 00:00:00	1	TCSA3	100	1.32	132.0	1
418	2019-03-07 00:00:00	2	KROT3	200	10.41	2082.0	1
423	2019-03-12 00:00:00	2	POMO4	100	4.16	416.0	1
425	2019-03-15 00:00:00	2	MOVI3	200	12.17	2434.0	1
426	2019-03-15 00:00:00	2	JHSF3	100	2.5	250.0	1
427	2019-03-15 00:00:00	2	JHSF3	45	2.51	112.95	1
432	2019-03-19 00:00:00	2	GGBR4	400	15.89	6356.0	1
433	2019-03-20 00:00:00	2	VIVR3	800	0.47	376.0	1
434	2019-03-20 00:00:00	2	BIDI4	1	47.15	47.15	1
437	2019-03-20 00:00:00	2	VIVR3	7	0.46	3.22	1
442	2019-04-02 00:00:00	2	ROMI3	400	10.32	4128.0	1
447	2019-04-04 00:00:00	2	ROMI3	200	10.31	2062.0	1
455	2019-05-16 00:00:00	2	JSLG3	200	12.05	2410.0	1
460	2019-05-28 00:00:00	2	STBP3	100	3.8	380.0	1
461	2019-05-28 00:00:00	2	TCSA3	59	1.3	76.7	1
462	2019-06-06 00:00:00	2	TCSA3	300	1.31	393.0	1
463	2019-07-01 00:00:00	2	USIM5	600	9.06	5436.0	1
464	2019-07-02 00:00:00	2	STBP3	700	4.5	3150.0	1
465	2019-07-03 00:00:00	2	POMO4	900	3.95	3555.0	1
466	2019-07-08 00:00:00	2	STBP3	1000	4.8	4800.0	1
\.


--
-- Name: operacoes_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.operacoes_id_seq', 469, true);


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
-- Name: carteira_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres; Tablespace: 
--

ALTER TABLE ONLY public.carteira
    ADD CONSTRAINT carteira_pkey PRIMARY KEY (id);


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
-- Name: usuario_fkey; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.carteira
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
