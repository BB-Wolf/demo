<?php

namespace Sprint\Migration;


class ASTSEQUEL143Data20220209000617 extends Version
{
    protected $description = "Данные для теста характеристик";

    protected $moduleVersion = "4.0.5";
    public function up()
    {

        $helper = $this->getHelperManager();

        if ($iblockId = $helper->Iblock()->getIblockIdIfExists('catalog', 'catalog')) {
            if ($idMainSect = $this->createSection($iblockId, "Сельскохозяйтсвенная техника", "agricultural", 120)) {
                if ($sectionId = $this->createSection($iblockId, "Тракторы", "traktory", 212, $idMainSect)) {
                    $elems = [];
                    for ($i = 0; $i <= 5; $i++) {
                        $elems[] = $helper->Iblock()->saveElement(
                            $iblockId,
                            [
                                "NAME"              => "Трактор с характеристиками " . $i,
                                "SORT"              => 10 + $i,
                                "CODE"              => "chars-traktor-" . $i,
                                "IBLOCK_SECTION_ID" => $sectionId,
                                "PREVIEW_TEXT"      => 'Сельскохозяйственная техника John Deere',
                                'PREVIEW_TEXT_TYPE' => 'html',
                                'PREVIEW_PICTURE'  => \CFile::MakeFileArray($_SERVER['DOCUMENT_ROOT'] . '/dist/tmp/card-tech/image-1.png'),
                            ],
                            [
                                'T_CARRYING' => rand(10, 100),
                                'T_SPEED' => rand(10, 100),
                                'T_LIFT' => rand(10, 100),
                                'T_POWER' => rand(10, 100),
                                'T_DIGGING_DEPTH' => rand(10, 100),
                                'T_BUCKET_VOLUME' => rand(10, 100),
                                'T_BOOM_WIDTH' => rand(10, 100),
                                'T_ENGINE' => rand(10, 100),
                                'T_VALUE' => rand(10, 100),
                                'T_NOMINAL_POWER' => rand(10, 100),
                                'T_CHEMICAL_TANK' => rand(10, 100),
                                'CHAR_FILE' => \CFile::MakeFileArray($_SERVER['DOCUMENT_ROOT'] . '/dist/tmp/card-tech/image-1.png'),
                                'IMG_TAB' => \CFile::MakeFileArray($_SERVER['DOCUMENT_ROOT'] . '/dist/tmp/card-tech/image-1.png'),
                                'IMG_MOBILE' => \CFile::MakeFileArray($_SERVER['DOCUMENT_ROOT'] . '/dist/tmp/card-tech/image-1.png'),
                                'IMG_SMALL' => \CFile::MakeFileArray($_SERVER['DOCUMENT_ROOT'] . '/dist/tmp/card-tech/image-1.png'),
                            ]
                        );
                    }

                    $helper->Iblock()->saveElement(
                        $iblockId,
                        [
                            "NAME"              => "Трактор связанный с другими с характеристиками",
                            "SORT"              => 20,
                            "CODE"              => "chars-traktor-link",
                            "IBLOCK_SECTION_ID" => $sectionId,
                            "PREVIEW_TEXT"      => 'Сельскохозяйственная техника John Deere',
                            'PREVIEW_TEXT_TYPE' => 'html',
                            'PREVIEW_PICTURE'  => \CFile::MakeFileArray($_SERVER['DOCUMENT_ROOT'] . '/dist/tmp/card-tech/image-1.png'),
                        ],
                        [
                            'T_CARRYING' => rand(10, 100),
                            'T_SPEED' => rand(10, 100),
                            'T_LIFT' => rand(10, 100),
                            'T_POWER' => rand(10, 100),
                            'T_DIGGING_DEPTH' => rand(10, 100),
                            'T_BUCKET_VOLUME' => rand(10, 100),
                            'T_BOOM_WIDTH' => rand(10, 100),
                            'T_ENGINE' => rand(10, 100),
                            'T_VALUE' => rand(10, 100),
                            'T_NOMINAL_POWER' => rand(10, 100),
                            'T_CHEMICAL_TANK' => rand(10, 100),
                            'SIMILAR' => $elems,
                            'CHAR_FILE' => \CFile::MakeFileArray($_SERVER['DOCUMENT_ROOT'] . '/dist/tmp/card-tech/image-1.png'),
                            'IMG_TAB' => \CFile::MakeFileArray($_SERVER['DOCUMENT_ROOT'] . '/dist/tmp/card-tech/image-1.png'),
                            'IMG_MOBILE' => \CFile::MakeFileArray($_SERVER['DOCUMENT_ROOT'] . '/dist/tmp/card-tech/image-1.png'),
                            'IMG_SMALL' => \CFile::MakeFileArray($_SERVER['DOCUMENT_ROOT'] . '/dist/tmp/card-tech/image-1.png'),

                        ]
                    );
                }
            }
        }
    }

    public function down()
    {
        //your code ...
    }

    private function createSection($blockId, $name, $code, $sort = 500, $parentId = null, $needUpdateIfExist = false)
    {
        $aSection = \CIBlockSection::GetList(
            [],
            [
                '=CODE'     => $code,
                'IBLOCK_ID' => $blockId
            ],
            false,
            ['ID'],
            false
        )->Fetch();
        $id = null;
        if ($aSection) {
            $id = $aSection['ID'];
            if (!$needUpdateIfExist) {
                return $id;
            }
        }

        $bs = new \CIBlockSection;
        $arFields = [
            "ACTIVE"            => 'Y',
            'CODE'              => $code,
            "IBLOCK_SECTION_ID" => $parentId,
            "IBLOCK_ID"         => $blockId,
            "NAME"              => $name,
            "SORT"              => $sort,
        ];

        if ($id > 0) {
            $bs->Update($id, $arFields);
        } else {
            $id = $bs->Add($arFields);
        }

        if ($id) {
            return $id;
        }

        throw new \Exception($bs->LAST_ERROR);
    }
}
