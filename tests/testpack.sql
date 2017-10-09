drop table opwTexts;
drop table opwHeader;

create table opwHeader (hid numeric(2) not null, hc numeric(2) not null, hheader varchar2(30), hheader2 varchar2(100));
alter table opwHeader add constraint opwHeader_pk primary key(hid)enable;

create table opwTexts (tid numeric(3) not null,thid numeric(2) not null, ttext varchar2(1000));
alter table opwTexts add constraint opwTexts_fk1 foreign key(thid)references opwHeader (hid) enable;
alter table opwTexts add constraint opwTexts_pk primary key(tid,thid)enable;

create or replace package PCK_OPW_TEST is

  procedure prc_get_chapters(ahid in number,
                             ahc in out number,
                             aheader out varchar2,
                             aheader2 out varchar2,
                             achapters out sys_refcursor);

end PCK_OPW_TEST;
/
create or replace package body PCK_OPW_TEST is

  procedure prc_get_chapters(ahid in number,
                             ahc in out number,
                             aheader out varchar2,
                             aheader2 out varchar2,
                             achapters out sys_refcursor) is
   /* also try with ahid not in (1-4) and catch the exception in php */
   lhc opwheader.hc%type;
  begin
  
  
    select hheader,
           hheader2,
           hc
      into aheader,
           aheader2,
           lhc
      from opwheader
     where hid = ahid;
    
    ahc := lhc + ahc;
    
    update opwheader set hc = ahc where hid = ahid;
    
    open achapters for
      select tid,ttext from opwtexts where THID = 1 order by tid;
  end prc_get_chapters;

end PCK_OPW_TEST;
/


insert into opwHeader values(1,0,'Chapter I','HOW CANDIDE WAS BROUGHT UP IN A MAGNIFICENT CASTLE, AND HOW HE WAS
EXPELLED THENCE.');
insert into opwHeader values(2,0,'Chapter II','WHAT BECAME OF CANDIDE AMONG THE BULGARIANS.');
insert into opwHeader values(3,0,'Chapter III','HOW CANDIDE MADE HIS ESCAPE FROM THE BULGARIANS, AND WHAT AFTERWARDS
BECAME OF HIM.');
insert into opwHeader values(4,0,'Chapter IV','HOW CANDIDE FOUND HIS OLD MASTER PANGLOSS, AND WHAT HAPPENED TO THEM.');

insert into opwTexts values (1,1,'In a castle of Westphalia, belonging to the Baron of
Thunder-ten-Tronckh, lived a youth, whom nature had endowed with the
most gentle manners. His countenance was a true picture of his soul. He
combined a true judgment with simplicity of spirit, which was the
reason, I apprehend, of his being called Candide. The old servants of
the family suspected him to have been the son of the Barons sister, by
a good, honest gentleman of the neighborhood, whom that young lady would
never marry because he had been able to prove only seventy-one
quarterings, the rest of his genealogical tree having been lost through
the injuries of time.');
insert into opwTexts values (1,2,'The Baron was one of the most powerful lords in Westphalia, for his
castle had not only a gate, but windows. His great hall, even, was hung
with tapestry. All the dogs of his farm-yards formed a pack of hounds at
need; his grooms were his huntsmen; and the curate of the village was
his grand almoner. They called him "My Lord," and laughed at all his
stories.');
insert into opwTexts values (1,3,'The Barons lady weighed about three hundred and fifty pounds, and was
therefore a person of great consideration, and she did the honours of
the house with a dignity that commanded still greater respect. Her
daughter Cunegonde was seventeen years of age, fresh-coloured, comely,
plump, and desirable. The Barons son seemed to be in every respect
worthy of his father. The Preceptor Pangloss[1] was the oracle of the
family, and little Candide heard his lessons with all the good faith of
his age and character.');
insert into opwTexts values (2,1,'Candide, driven from terrestrial paradise, walked a long while without
knowing where, weeping, raising his eyes to heaven, turning them often
towards the most magnificent of castles which imprisoned the purest of
noble young ladies. He lay down to sleep without supper, in the middle
of a field between two furrows. The snow fell in large flakes. Next day
Candide, all benumbed, dragged himself towards the neighbouring town
which was called Waldberghofftrarbk-dikdorff, having no money, dying of
hunger and fatigue, he stopped sorrowfully at the door of an inn. Two
men dressed in blue observed him.');
insert into opwTexts values (2,2,'"Comrade," said one, "here is a well-built young fellow, and of proper
height."');
insert into opwTexts values (2,3,'They went up to Candide and very civilly invited him to dinner.');

insert into opwTexts values (3,1,'There was never anything so gallant, so spruce, so brilliant, and so
well disposed as the two armies. Trumpets, fifes, hautboys, drums, and
cannon made music such as Hell itself had never heard. The cannons first
of all laid flat about six thousand men on each side; the muskets swept
away from this best of worlds nine or ten thousand ruffians who infested
its surface. The bayonet was also a _sufficient reason_ for the death of
several thousands. The whole might amount to thirty thousand souls.
Candide, who trembled like a philosopher, hid himself as well as he
could during this heroic butchery.');
insert into opwTexts values (3,2,'At length, while the two kings were causing Te Deum to be sung each in
his own camp, Candide resolved to go and reason elsewhere on effects and
causes. He passed over heaps of dead and dying, and first reached a
neighbouring village; it was in cinders, it was an Abare village which
the Bulgarians had burnt according to the laws of war. Here, old men
covered with wounds, beheld their wives, hugging their children to their
bloody breasts, massacred before their faces; there, their daughters,
disembowelled and breathing their last after having satisfied the
natural wants of Bulgarian heroes; while others, half burnt in the
flames, begged to be despatched. The earth was strewed with brains,
arms, and legs.');
insert into opwTexts values (3,3,'Candide fled quickly to another village; it belonged to the Bulgarians;
and the Abarian heroes had treated it in the same way. Candide, walking
always over palpitating limbs or across ruins, arrived at last beyond
the seat of war, with a few provisions in his knapsack, and Miss
Cunegonde always in his heart. His provisions failed him when he arrived
in Holland; but having heard that everybody was rich in that country,
and that they were Christians, he did not doubt but he should meet with
the same treatment from them as he had met with in the Barons castle,
before Miss Cunegondes bright eyes were the cause of his expulsion
thence.');

insert into opwTexts values (4,1,'Candide fled quickly to another village; it belonged to the Bulgarians;
and the Abarian heroes had treated it in the same way. Candide, walking
always over palpitating limbs or across ruins, arrived at last beyond
the seat of war, with a few provisions in his knapsack, and Miss
Cunegonde always in his heart. His provisions failed him when he arrived
in Holland; but having heard that everybody was rich in that country,
and that they were Christians, he did not doubt but he should meet with
the same treatment from them as he had met with in the Barons castle,
before Miss Cunegondes bright eyes were the cause of his expulsion
thence.');
insert into opwTexts values (4,2,'"Alas!" said one wretch to the other, "do you no longer know your dear
Pangloss?"');
insert into opwTexts values (4,3,'"What do I hear? You, my dear master! you in this terrible plight! What
misfortune has happened to you? Why are you no longer in the most
magnificent of castles? What has become of Miss Cunegonde, the pearl of
girls, and natures masterpiece?"');

commit;