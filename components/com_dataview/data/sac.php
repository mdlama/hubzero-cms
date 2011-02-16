<?php
defined( '_JEXEC' ) or die( 'Restricted access' );

function get_sac()
{
	$link = get_db();
	$id = isset($_REQUEST['id'])? mysql_real_escape_string($_REQUEST['id']): false;

	//Data definition
	$dd['title'] = "The SAC Steel Project Database";
	$dd['table'] = 'sacdb';
	$dd['serverside'] = true;
//	$dd['db'] = array('host'=> 'neesud.neeshub.org', 'user'=>'userDB', 'pass' => 'userDB1_pass', 'name' => 'earthquakedata');
	
//	$dd['cols']['sacdb.Rank'] = array('label'=>'Rank', 'desc'=>'Select two or more items to compare side-by-side', 'data_type'=>'int', 'more_info'=>'sac|sacdb.Rank', 'compare'=>'compare', 'width'=>'70');
	$dd['cols']['sacdb.Test_ID'] = array('label'=>'Test ID');
	$dd['cols']['sacdb.Report'] = array('label'=>'Referenced<br />Report', 'desc'=>'URL link to the PDF file containing the referenced report', 'type'=>'link', 'link_label'=>'sacdb.Test_ID');
	$dd['cols']['sacdb.Connection_Drawing'] = array('label'=>'Connection<br />Drawing', 'desc'=>'Drawing extracted from reports that shows the connection detail.', 'type'=>'image');
	$dd['cols']['sacdb.Test_Drawing'] = array('label'=>'Test<br />Drawing', 'desc'=>'Drawing extracted from research report showing the test setup.', 'type'=>'image');
	$dd['cols']['sacdb.Tested_Connection_Photo'] = array('label'=>'Tested<br />Connection Photo', 'desc'=>'Photo extracted from research report that shows the connection at the completion of the test or test setup.', 'type'=>'image');
	$dd['cols']['sacdb.Moment_Rotation'] = array('label'=>'Moment-Rotation', 'desc'=>'Final moment rotation plot extracted from research report that shows the overall hysteresis plot.', 'type'=>'image');
	$dd['cols']['sacdb.Ref_Source'] = array('label'=>'Reference', 'desc'=>'This is the reference/citation of resources for the test performed.  The full references are listed at the following link:  http://www.sacsteel.org/connections/AppB.html');
	$dd['cols']['sacdb.Test_Date'] = array('label'=>'Test Date', 'desc'=>'Date(s) test was performed');
	$dd['cols']['sacdb.Lab'] = array('label'=>'Laboratory', 'desc'=>'Laboratory where test was performed');
	$dd['cols']['sacdb.Investigator'] = array('label'=>'Investigator', 'desc'=>'Name of investigator');
	$dd['cols']['sacdb.Engineer'] = array('label'=>'Engineer', 'desc'=>'Designer, for qualification tests');
	$dd['cols']['sacdb.Sponsor'] = array('label'=>'Sponsor', 'desc'=>'Sponsor of test');
	$dd['cols']['sacdb.Last_Update'] = array('label'=>'Last Update', 'desc'=>'Date of latest data entry/modification');
	$dd['cols']['sacdb.Intent'] = array('label'=>'Intention', 'desc'=>'Intended use of the connection being tested');
	$dd['cols']['sacdb.Condition_'] = array('label'=>'Condition', 'width'=>'120', 'truncate'=>'truncate', 'desc'=>'Condition notes are used to record material or fabrication defects, material reuse, or other pre-test conditions specifically reported in the source document');
	$dd['cols']['sacdb.F350_Type'] = array('label'=>'F350 Connection Type', 'desc'=>"Where applicable and appropriate, the tested connection is classified with one of the connection designations given in FEMA 350. This information is for the convenience of users studying a certain FEMA 350 connection type; it does not indicate conformance of the test specimen with FEMA 350. That is, the database may list an F350 TYPE even if some of the tested conditions do not exactly match those required for prequalification by FEMA 350. For example, FEMA 350 prequalification may be limited to certain member sizes or may require a specific weld access hole shape or beam web connection. A database connection may not meet those requirements but may match the general design intent and philosophy of the FEMA 350 connection type. For such a test, this database field usually lists the FEMA designation that most closely matches the test’s method of transferring flexural forces.  Database abbreviations in this field are the same as those given in FEMA 350:  \n\nPrequalified welded fully restrained (FR) connections:  \nWUF-B = Welded unreinforced flange-bolted web.  \nWUF-W = Welded unreinforced flange-welded web.  \nFF = Free flange.  \nWFP = Welded flange plate.  \nRBS = Reduced beam section.  \nBUEP =  Bolted unstiffened end plate.  \nBSEP = Bolted stiffened end plate.  \nBFP =  Bolted flange plate.  \n\nPrequalified partially restrained (PR) connections:  \nDST = Double split tee.  \n\nProprietary connections:  \nSP = Side plate.  \nSW = Slotted web.  \nBB = Bolted bracket.");
	$dd['cols']['sacdb.Type'] = array('label'=>'Connection Type', 'desc'=>'Description of the connection type.  Refer to the report for more information.');
	$dd['cols']['sacdb.Piece_Details'] = array('label'=>'Piece Details', 'width'=>'120', 'truncate'=>'truncate', 'desc'=>'This information expands on the Connection Type by describing the size, arrangement, or material of various plates, slots, or flange cuts. For PreNR connections, this item is generally “na.” As space is limited, database users should consult the listed references for complete details.  Most of the descriptions under Piece Details are self explanatory. Various types of Reduced Beam Section details are described in FEMA 267A (p. 7-26).');
	$dd['cols']['sacdb.Weld_Loc_n'] = array('label'=>'Weld Location', 'desc'=>"Weld location data indicates the assumed path of flexural forces through welds from the beam to the column. \n\nIn PreNR connections, for example, the weld location is between the beam flange and the column at both beam flanges, and is indicated as: “TB:bm-col.” In haunch connections, however, the weld may join the column to the haunch flange only, to the haunch flange and web, or to both the haunch and the beam flange. In fully bolted connections, weld location is “na.”  Refer to reports for more information.");
	$dd['cols']['sacdb.TWeldDet'] = array('label'=>'Top Flange: Weld Detail', 'desc'=>"Refers to the welding position, the welding sequence, and the treatment of backing bars for the welds that are traditionally designed to transfer flexural forces. \n\nAbbreviations used in the database include:  \nCP = Complete joint penetration groove weld, single-bevel.  \nDCP = CP weld made in the downhand position, typical of field (or shop) welds for new construction.  \nOHCP = CP weld made in the overhead position. Overhead welding simulates repair conditions and is sometimes specified to avoid weld access holes or passes interrupted by the beam web.  \nF = Fillet weld.  \nDoubBev  = Double-bevel complete penetration weld.  \n\nAbbreviations regarding the backing bar include:  \nYB = Backing bar used and left in placed.  \nNB = No backing bar used, for example in some shop welds to end plates for which no backing is needed.  \nBR = Backing bar removed after completion of the weld.  \nBW = Backing bar left in place but welded to the column in order to reduce potential notch effects.  \n\nRefer to reports for more information.");
	$dd['cols']['sacdb.BWeldDet'] = array('label'=>'Bottom Flange: Weld Detail', 'desc'=>"Refers to the welding position, the welding sequence, and the treatment of backing bars for the welds that are traditionally designed to transfer flexural forces. \n\nAbbreviations used in the database include:  \nCP = Complete joint penetration groove weld, single-bevel.  \nDCP = CP weld made in the downhand position, typical of field (or shop) welds for new construction.  \nOHCP = CP weld made in the overhead position. Overhead welding simulates repair conditions and is sometimes specified to avoid weld access holes or passes interrupted by the beam web.  \nF = Fillet weld.  \nDoubBev  = Double-bevel complete penetration weld.  \n\nAbbreviations regarding the backing bar include:  \nYB = Backing bar used and left in placed.  \nNB = No backing bar used, for example in some shop welds to end plates for which no backing is needed.  \nBR = Backing bar removed after completion of the weld.  \nBW = Backing bar left in place but welded to the column in order to reduce potential notch effects.  Refer to reports for more information.");
	$dd['cols']['sacdb.WType'] = array('label'=>'Weld Type', 'width'=>'200', 'desc'=>'Weld type refers to the electrode used for the welds that are traditionally designed to transfer flexural forces.  Refer to reports for more information.');
	$dd['cols']['sacdb.ColStl'] = array('label'=>'Column: Steel Grade', 'desc'=>'Steel grade of column tested');
	$dd['cols']['sacdb.CFm'] = array('label'=>'Column Flange: Coupon F<sub>y</sub><br />[ksi]', 'desc'=>'The mill value refers to a mill test report or mill certification for the heat of steel from which the member came.');
	$dd['cols']['sacdb.CWm'] = array('label'=>'Column Web: Mill F<sub>y</sub><br />[ksi]', 'desc'=>'The mill value refers to a mill test report or mill certification for the heat of steel from which the member came.');
	$dd['cols']['sacdb.CFc'] = array('label'=>'Column Flange: Coupon F<sub>y</sub><br />[ksi]', 'desc'=>'The coupon value refers to the result of a coupon test from the actual member.  Additional details on coupon testing may be available in the cited reference.');
	$dd['cols']['sacdb.CWc'] = array('label'=>'Column Web: Coupon F<sub>y</sub><br />[ksi]', 'desc'=>'The coupon value refers to the result of a coupon test from the actual member.  Additional details on coupon testing may be available in the cited reference.');
	$dd['cols']['sacdb.Column_'] = array('label'=>'Column Size', 'desc'=>"Size/Shape of column.  Most of the tested sections were standard wide flange shapes. \n\nThe few special cases are noted in the database with the following abbreviations:  \nbu = Section built-up from plates.  \nBX = Box or tube section.  \nCFT = Concrete-filled tube.  \ncruc = Cruciform section, fabricated from T shapes attached to the web of a wide-flange.  \nsim = An H section or a European shape similar in size to the listed wide flange.  \nwk = Weak axis column orientation with the beam framing to the column web. Unless noted “wk,” all beams frame to the column flange.");
	$dd['cols']['sacdb.ColWt'] = array('label'=>'Column Weight<br />[lbs]', 'desc'=>'Weight of the column');
	$dd['cols']['sacdb.ColHt'] = array('label'=>'Column Height<br />[feet]', 'desc'=>'Height of the Column.  For tests with column tip control, the column height is used to calculate equivalent story drift.');
	$dd['cols']['sacdb.BmStl'] = array('label'=>'Beam: Steel Grade', 'desc'=>'Steel grade of beam tested');
	$dd['cols']['sacdb.BFm'] = array('label'=>'Beam Flange: Mill F<sub>y</sub><br />[ksi]', 'desc'=>'The mill value refers to a mill test report or mill certification for the heat of steel from which the member came.');
	$dd['cols']['sacdb.BWm'] = array('label'=>'Beam Web: Mill F<sub>y</sub><br />[ksi]', 'desc'=>'The mill value refers to a mill test report or mill certification for the heat of steel from which the member came.');
	$dd['cols']['sacdb.BFc'] = array('label'=>'Beam Flange: Coupon F<sub>y</sub><br />[ksi]', 'desc'=>'The coupon value refers to the result of a coupon test from the actual member.  Additional details on coupon testing may be available in the cited reference.');
	$dd['cols']['sacdb.BWc'] = array('label'=>'Beam Web: Coupon F<sub>y</sub><br />[ksi]', 'desc'=>'The coupon value refers to the result of a coupon test from the actual member.  Additional details on coupon testing may be available in the cited reference.');
	$dd['cols']['sacdb.Beam'] = array('label'=>'Beam Size', 'desc'=>"Size/Shape of beam.  Most of the tested sections were standard wide flange shapes. \n\nThe few special cases are noted in the database with the following abbreviations:  \nbu = Section built-up from plates.  \nBX = Box or tube section.  \nCFT = Concrete-filled tube.  \ncruc = Cruciform section, fabricated from T shapes attached to the web of a wide-flange.  \nsim = An H section or a European shape similar in size to the listed wide flange.  \nwk = Weak axis column orientation with the beam framing to the column web. Unless noted “wk,” all beams frame to the column flange.");
	$dd['cols']['sacdb.BmWt'] = array('label'=>'Beam Weight<br />[in.]', 'desc'=>'Weight of the beam');
	$dd['cols']['sacdb.Lcl'] = array('label'=>'Length: tip to centerline<br />[in.]', 'desc'=>'Beam tip-to-column centerline length.');
	$dd['cols']['sacdb.Lh'] = array('label'=>'Length: hinge to centerline<br />[in.]', 'desc'=>'Hinge-to-column centerline length');
	$dd['cols']['sacdb.CntPl'] = array('label'=>'Thickness: Continuity Plate<br />[in.]', 'desc'=>'Continuity plate thicknesses.  If the source document indicates a plate but does not report its thickness, the value “y” or “yes” is given. Refer to cited references for specific test details.');
	$dd['cols']['sacdb.DbPl'] = array('label'=>'Thickness: Doubler Plates<br />[in.]', 'desc'=>'Double plate thicknesses.  If the source document indicates a plate but does not report its thickness, the value “y” or “yes” is given. Refer to cited references for specific test details.');
	$dd['cols']['sacdb.Web_Conn'] = array('label'=>'Web: Connection Type', 'desc'=>"The web or “shear” connection refers to the joining of the beam web to the column.  \n\nFour typical types are abbreviated as follows and illustrated in Figure 2.  \nB = Bolted, \nW = Welded. Beam web welded directly to the column. A shear lug with erection bolts is typically present and may serve as a backing for the beam web-to-column weld.  \nWB = Bolted with supplemental shear tab welds designed to provide some of the connection’s flexural capacity. In some connections intended for repair or modification, an existing bolted shear tab is welded to the beam web along its entire perimeter. In these cases, the shear connection is classified as Wpl whether or not the bolts remain in place.  \nWpl = Welded to the shear plate. The beam web is welded to the shear tab which is welded in turn to the column.");
	$dd['cols']['sacdb.Slab'] = array('label'=>'Slab Description', 'desc'=>'Description of slab, if applicable.  "n" refers to "none".');
	$dd['cols']['sacdb.Col_Axial'] = array('label'=>'Column: Axial Load', 'desc'=>"With most test specimens, only local “seismic” loads representing the effects of lateral deformation on a single beam-column substructure were applied. Some tests attempted to account for gravity loads or other frame effects as well. These loads, typically applied as axial loads to the column, are recorded here. \n\nThe following abbreviations are used (other descriptions are self-explanatory): \nC:  Compressive axial load applied to column. \nT: Tensile axial load applied to column. \nn: none.");
	$dd['cols']['sacdb.Config'] = array('label'=>'Configuration', 'desc'=>'The two most common test configurations, “1side” and “2side”.  Two-sided configurations are listed in the database as two separate tests.');
	$dd['cols']['sacdb.Control'] = array('label'=>'Tip Control<br />(Beam or Column)', 'desc'=>'The two methods of application are “beam tip control” and “column tip control”. With beam tip control, the column ends are restrained and loads are applied at the end of the beam. With column tip control, the beam end and the bottom of the column are restrained while load is applied to the top of the column. Although the two methods involve different ways of recording response, the database records principal results in terms of equivalent story drift, which can be measured from either type of test.  bm refers to"beam tip control", whereas col refers to "column tip control".');
	$dd['cols']['sacdb.Loading'] = array('label'=>'Loading Protocol', 'desc'=>"The nature of the applied load is either (quasi-) static or dynamic. Static loading is generally either cyclic or monotonic. Dynamic loading can be either cyclic or representative of a specific earthquake time-history. Most of the tests in the database used static cyclic loading. The precise load history, or protocol, can vary in terms of the target force or displacement levels or the number of cycles applied at each level.");
	$dd['cols']['sacdb.Dy'] = array('label'=>'∆<sub>reference</sub><br />[in.]', 'desc'=>"Cyclic loading typically involves displacements that are increased with each cycle. The amplitude of the applied displacements is usually set in advance as a multiple of a value called the reference deflection, ∆reference. The reference deflection is usually calculated, not measured, as the value at which significant yielding is expected to begin. It is sometimes arbitrarily assigned.  Since ∆reference is not consistently defined or calculated, the database uses the 1% deflection as its basic deformation measurement. However, because many earlier tests reported results in terms of ∆reference, it is recorded in the database. More recent tests typically use load protocols based on total story drift, not yield deflection. Some earlier tests also report a yield load, Py, that corresponds to the reference deflection. These values have little meaning and are no longer recorded in the database.");
	$dd['cols']['sacdb.Notes'] = array('label'=>'Notes', 'desc'=>"This field identifies and abbreviates typical conditions and common exceptions. It can also be used for sorting or querying the database. Unless noted, the following conditions apply to all tests:  A572Gr50 or Dual Cert column, A36 beam.  FCAW beam flange-to-column weld with notch-toughness requirement.  No applied column loads.  No slab.  One-sided beam-column configuration.  Static cyclic loading.\n\nThe following notes indicate exceptions to the typical conditions above:  \n\n1:  \na = A572Gr50 or Dual Cert beam or beam nominal yield strength not reported.  \nb = A36 column or column nominal yield strength not reported.  \nc = A913 column.  \n\n2: \na = FCAW weld without notch-toughness requirement at one or both beam flanges.  \nb =SMAW weld. c = Weld type not reported.  \n\n3: \na = Column axial tension applied.  \nb = Column axial compression applied.  \nc = Out-of-plane moments/axial loads applied to column to simulate bi-directional loading.  \n\n4: Composite beam behaviorsimulated with partial slab.  \n\n5: Listed test is one side of a two-sided configuration.  \n\n6: \na =  Static monotonic loading. \nb = Dynamic loading, cyclic or time-history. \nc  = Static cyclic, near-field protocol.");
	$dd['cols']['sacdb.Y_xDy_'] = array('label'=>'Observed: Fy', 'desc'=>"The database records observed inelasticity with the pattern abbreviations of FEMA 267 (Section 3.2). The letters “T” or “B” in front of the damage type indicate whether the condition was observed at the beam top flange or the beam bottom flange or both (“TB”). The location of the damage in the specimen may also be recorded. In addition, the database records the approximate point in the loading history where the condition was observed by following the damage type with a bracketed value. If the bracketed value is in inches, for example, it indicates the peak displacement of the cycle in which the specified damage was observed. If the bracketed value is a unit-less integer, it represents a multiple of the reference deflection Dy. Otherwise, bracketed values may represent displacement in inches or total drift in percentage. If source documents do not specify when the observation was made, other bracket values (such as “first” or “end”) may be used to convey the sequence of damage.  This abbreviation system is illustrated by the following example observations:  TBG2outsideCP[1.5] = Yielding (indicated by G2) in the top and bottom beam flanges (TB) beyond the end of the cover plate (outsideCP), first observed sometime during one of the 1.5Dy cycles.  BC3[1],P5[2%],S5[end] = Fracture through the column flange (BC3) during the 1Dy cycles, followed by fracture into the column web (P5) during the 2% drift cycles, and bolt damage (S5) at a deformation level reported only as “the end of the test.”  Refer to reports for more information.");
	$dd['cols']['sacdb.B_xDy_'] = array('label'=>'Observed: Buckling', 'desc'=>"The database records observed inelasticity with the pattern abbreviations of FEMA 267 (Section 3.2). The letters “T” or “B” in front of the damage type indicate whether the condition was observed at the beam top flange or the beam bottom flange or both (“TB”). The location of the damage in the specimen may also be recorded. In addition, the database records the approximate point in the loading history where the condition was observed by following the damage type with a bracketed value. If the bracketed value is in inches, for example, it indicates the peak displacement of the cycle in which the specified damage was observed. If the bracketed value is a unit-less integer, it represents a multiple of the reference deflection Dy. Otherwise, bracketed values may represent displacement in inches or total drift in percentage. If source documents do not specify when the observation was made, other bracket values (such as “first” or “end”) may be used to convey the sequence of damage.  This abbreviation system is illustrated by the following example observations:  TBG2outsideCP[1.5] = Yielding (indicated by G2) in the top and bottom beam flanges (TB) beyond the end of the cover plate (outsideCP), first observed sometime during one of the 1.5Dy cycles.  BC3[1],P5[2%],S5[end] = Fracture through the column flange (BC3) during the 1Dy cycles, followed by fracture into the column web (P5) during the 2% drift cycles, and bolt damage (S5) at a deformation level reported only as “the end of the test.”  Refer to reports for more information.");
	$dd['cols']['sacdb.F_xDy_'] = array('label'=>'Observed: Fracture', 'desc'=>"The database records observed inelasticity with the pattern abbreviations of FEMA 267 (Section 3.2). The letters “T” or “B” in front of the damage type indicate whether the condition was observed at the beam top flange or the beam bottom flange or both (“TB”). The location of the damage in the specimen may also be recorded. In addition, the database records the approximate point in the loading history where the condition was observed by following the damage type with a bracketed value. If the bracketed value is in inches, for example, it indicates the peak displacement of the cycle in which the specified damage was observed. If the bracketed value is a unit-less integer, it represents a multiple of the reference deflection Dy. Otherwise, bracketed values may represent displacement in inches or total drift in percentage. If source documents do not specify when the observation was made, other bracket values (such as “first” or “end”) may be used to convey the sequence of damage.  This abbreviation system is illustrated by the following example observations:  TBG2outsideCP[1.5] = Yielding (indicated by G2) in the top and bottom beam flanges (TB) beyond the end of the cover plate (outsideCP), first observed sometime during one of the 1.5Dy cycles.  BC3[1],P5[2%],S5[end] = Fracture through the column flange (BC3) during the 1Dy cycles, followed by fracture into the column web (P5) during the 2% drift cycles, and bolt damage (S5) at a deformation level reported only as “the end of the test.”  Refer to reports for more information.");
	$dd['cols']['sacdb.FracRate'] = array('label'=>'Fracture rate', 'desc'=>"Fracture rate.\n\nFracture rate information is recorded only if specifically given in the source document and is usually a direct quote.");
	$dd['cols']['sacdb.Stop_Condition'] = array('label'=>'Stop Condition', 'desc'=>"Conditons present at the stop action.\n\nThe most common stop conditions are:\n\nFracture = The test was stopped because the observed fracture made additional testing either pointless or impossible. Similarly, tests may have been stopped due to severe strength or stiffness degradation.\n\nComplete = The test was stopped because the loading protocol was complete or because acceptance criteria were met.\n\nEquipment Limit = The test was stopped because the support structure, loading devices, or monitoring equipment could not tolerate further loads or deformations.");
	$dd['cols']['sacdb.UndefPRmx'] = array('label'=>'Undefined θ<sub>plastic</sub><br />[radians]', 'width'=>'120', 'truncate'=>'truncate','desc'=>"While the database emphasizes total (that is, elastic plus inelastic) story drift, common acceptance criteria for connection tests have been based on plastic rotation. For this reason, reference documents for most tests report rotation in some format. Unfortunately, the various references define rotation in different ways, and many give no definition for reported values. A complete definition of rotation should be clear about whether total (elastic plus plastic) or just plastic rotation is considered and should state which components (beam, column, panel zone, etc.) have contributed to the reported value. If the reported rotation is calculated as the quotient of a displacement and a member length, the length used should be clearly defined as well.  An effort was made to record rotation values wherever possible, even though reported definitions were sometimes unclear or inconsistent. In some cases an approximate elastic contribution was subtracted to obtain the listed plastic rotation.  Some source documents reported plastic rotation at the beam hinge. This typically includes the beam contribution only and is calculated based on the length from the beam tip to the hinge. In order to convert this to a corresponding rotation at the column centerline (per FEMA 267A, Figure 6.6.5-1), the hinge-to-column centerline distance, Lh, is also recorded if available. Other documents reported plastic rotation due to panel zone distortion alone. Still others reported an overall plastic rotation relative to the column centerline with contributions from all components.  Most reports, however, did not carefully define rotations, so reported values are listed as undefined. However, many of these same documents implied that reported values were calculated where bending moment was measured, that is, at the column face.");
	$dd['cols']['sacdb.PHPRmax'] = array('label'=>'Hinge: θ<sub>plastic</sub><br />[radians]', 'desc'=>'Plastic Rotation at hinge');
	$dd['cols']['sacdb.PZPRmax'] = array('label'=>'Panel Zone: θ<sub>plastic</sub><br />[radians]', 'desc'=>'Plastic rotation in panel zone');
	$dd['cols']['sacdb.CLPRmax'] = array('label'=>'Column Centerline: θ<sub>plastic</sub><br />[radians]', 'desc'=>'Plastic rotation at column centerline');
	$dd['cols']['sacdb.ED_k_'] = array('label'=>'Energy Dissipation<br />[ft-k]', 'desc'=>"Energy Dissapated.\n\nNote that many values have been converted from the original units reported in the test reports in order to have consistent units.");
	$dd['cols']['sacdb.Dmax'] = array('label'=>'∆<sub>max</sub><br />[in.]', 'desc'=>"Maximum Deflection.\n\nNote: Most of the database source documents report the relationship of the actuator displacement to either the applied force or a calculated moment. If both are reported, the database records force values only. If only moment is reported, the database records those instead. The maximum force (or moment) is the maximum absolute value of force (or moment) reported at any displacement value less than or equal to Dmax.\n\nWhere applicable, force values are reported in blue font, whereas moments are reported in red font.");
	$dd['cols']['sacdb.P_Dmax_color'] = array('hide'=>'hide');
	$dd['cols']['sacdb.P_Dmax'] = array('label'=>'P (or M) at ∆<sub>max</sub><br />[kips or in-kips]', 'desc'=>"Maximum force (Pmax) or maximum moment (Mmax) at the maximum deflection (∆max).\n\nNote: Most of the database source documents report the relationship of the actuator displacement to either the applied force or a calculated moment. If both are reported, the database records force values only. If only moment is reported, the database records those instead. The maximum force (or moment) is the maximum absolute value of force (or moment) reported at any displacement value less than or equal to Dmax.\n\nForce values are represented in blue, and moment values are in red font.", 'opmod'=>array('switch'=>'sacdb.P_Dmax_color', 'case'=>array('Force'=>'set_color|#00F', 'Moment'=>'set_color|#F00')));
	$dd['cols']['sacdb.D_Pmax'] = array('label'=>'∆ at P/M<sub>max</sub><br />[in.]', 'desc'=>"Deflection at maximum force (Pmax) or maximum moment (Mmax).\n\nNote: Most of the database source documents report the relationship of the actuator displacement to either the applied force or a calculated moment. If both are reported, the database records force values only. If only moment is reported, the database records those instead. The maximum force (or moment) is the maximum absolute value of force (or moment) reported at any displacement value less than or equal to Dmax.");
	$dd['cols']['sacdb.P_Mmax_color'] = array('hide'=>'hide');
	$dd['cols']['sacdb.P_Mmax'] = array('label'=>'P/M<sub>max</sub><br />[kips or in-kips]', 'desc'=>"Maximum force (Pmax) or maximum moment (Mmax).\n\nNote: Most of the database source documents report the relationship of the actuator displacement to either the applied force or a calculated moment. If both are reported, the database records force values only. If only moment is reported, the database records those instead. The maximum force (or moment) is the maximum absolute value of force (or moment) reported at any displacement value less than or equal to Dmax.\n\nForce values are represented in blue, and moment values are in red font.", 'opmod'=>array('switch'=>'sacdb.P_Mmax_color', 'case'=>array('Force'=>'set_color|#00F', 'Moment'=>'set_color|#F00')));
	$dd['cols']['sacdb.1_D'] = array('label'=>'1% Deflection<br />[in.]', 'desc'=>"Approximately equal to the deflection corresponding to a 0.01 radian rotation between the beam and column chords.");
	$dd['cols']['sacdb.P1_D_color'] = array('hide'=>'hide');
	$dd['cols']['sacdb.P1_D'] = array('label'=>'P or M (1% deflection)<br />[kips or in-kips]', 'desc'=>"Force or moment at 1% equivalent story drift.\n\nNote: Most of the database source documents report the relationship of the actuator displacement to either the applied force or a calculated moment. If both are reported, the database records force values only. If only moment is reported, the database records those instead. The maximum force (or moment) is the maximum absolute value of force (or moment) reported at any displacement value less than or equal to Dmax.\n\nForce values are represented in blue, and moment values are in red font.", 'opmod'=>array('switch'=>'sacdb.P1_D_color', 'case'=>array('Force'=>'set_color|#00F', 'Moment'=>'set_color|#F00')));
	$dd['cols']['sacdb.P2_D_color'] = array('hide'=>'hide');
	$dd['cols']['sacdb.P2_D'] = array('label'=>'P or M (2% deflection)<br />[kips or in-kips]', 'desc'=>"Force or moment at 2% equivalent story drift.\n\nNote: Most of the database source documents report the relationship of the actuator displacement to either the applied force or a calculated moment. If both are reported, the database records force values only. If only moment is reported, the database records those instead. The maximum force (or moment) is the maximum absolute value of force (or moment) reported at any displacement value less than or equal to Dmax.\n\nForce values are represented in blue, and moment values are in red font.", 'opmod'=>array('switch'=>'sacdb.P2_D_color', 'case'=>array('Force'=>'set_color|#00F', 'Moment'=>'set_color|#F00')));
	$dd['cols']['sacdb.P3_D_color'] = array('hide'=>'hide');
	$dd['cols']['sacdb.P3_D'] = array('label'=>'P or M (3% deflection)<br />[kips or in-kips]', 'desc'=>"Force or moment at 3% equivalent story drift.\n\nNote: Most of the database source documents report the relationship of the actuator displacement to either the applied force or a calculated moment. If both are reported, the database records force values only. If only moment is reported, the database records those instead. The maximum force (or moment) is the maximum absolute value of force (or moment) reported at any displacement value less than or equal to Dmax.\n\nForce values are represented in blue, and moment values are in red font.", 'opmod'=>array('switch'=>'sacdb.P3_D_color', 'case'=>array('Force'=>'set_color|#00F', 'Moment'=>'set_color|#F00')));
	$dd['cols']['sacdb.P4_D_color'] = array('hide'=>'hide');
	$dd['cols']['sacdb.P4_D'] = array('label'=>'P or M (4% deflection)<br />[kips or in-kips]', 'desc'=>"Force or moment at 4% equivalent story drift.\n\nNote: Most of the database source documents report the relationship of the actuator displacement to either the applied force or a calculated moment. If both are reported, the database records force values only. If only moment is reported, the database records those instead. The maximum force (or moment) is the maximum absolute value of force (or moment) reported at any displacement value less than or equal to Dmax.\n\nForce values are represented in blue, and moment values are in red font.", 'opmod'=>array('switch'=>'sacdb.P4_D_color', 'case'=>array('Force'=>'set_color|#00F', 'Moment'=>'set_color|#F00')));
	$dd['cols']['sacdb.Dtmx_Lcl'] = array('label'=>'Total Story Drift', 'desc'=>'Total story drift is calculated as the ratio of ∆max to the relevant length (column height or beam cantilever length) and expressed as a percentage. For the range of interest here, total story drift is approximately equivalent to the relative chord rotation between beam and column centerlines, expressed in radians.');
	$dd['cols']['sacdb.Add_l_Result'] = array('label'=>'Additional Result', 'desc'=>'Additional results in other formats');

	if ($id) {
		$dd['where'][] = array('field'=>'sacdb.Rank', 'value'=>$id);
		$dd['single'] = true;
	}
	
	$sql = query_gen($dd);

	$res = get_results($sql, $dd);

	return $res;
}
?>
