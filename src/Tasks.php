<?php

namespace IsaEken\Picpurify;

class Tasks
{
    const PornModeration = 'porn_moderation';
    const SuggestiveNudityModeration = 'suggestive_nudity_moderation';
    const GoreModeration = 'gore_moderation';
    const MoneyModeration = 'money_moderation';
    const WeaponModeration = 'weapon_moderation';
    const DrugModeration = 'drug_moderation';
    const HateSignModeration = 'hate_sign_moderation';
    const ObsceneGestureModeration = 'obscene_gesture_moderation';
    const QrCodeModeration = 'qr_code_moderation';

    const FaceDetection = 'face_detection';
    const FaceAgeDetection = 'face_age_detection';
    const FaceGenderDetection = 'face_gender_detection';
    const FaceGenderAgeDetection = 'face_gender_age_detection';

    const ContentModerationProfile = 'content_moderation_profile';

    /**
     * @return string[]
     */
    public static function moderations(): array
    {
        return [
            static::PornModeration,
            static::SuggestiveNudityModeration,
            static::GoreModeration,
            static::MoneyModeration,
            static::WeaponModeration,
            static::DrugModeration,
            static::HateSignModeration,
            static::ObsceneGestureModeration,
            static::QrCodeModeration,
        ];
    }

    /**
     * @return string[]
     */
    public static function detections(): array
    {
        return [
            self::FaceDetection,
            self::FaceAgeDetection,
            self::FaceGenderDetection,
            self::FaceGenderAgeDetection,
        ];
    }

    /**
     * @return string[]
     */
    public static function profiles(): array
    {
        return [
            self::ContentModerationProfile,
        ];
    }
}
