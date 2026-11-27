@extends('layouts.invitation')

@section('content')
    @include('invitation.sections.cover', ['hero' => $sections['section_1_hero'], 'guestName' => $guestName])

    <main id="integrated-content" data-track-visit-url="{{ route('visit.track') }}">
        <x-invitation.audio-player />

        @include('invitation.sections.hero', ['hero' => $sections['section_1_hero'], 'guestName' => $guestName])
        @include('invitation.sections.countdown', ['hero' => $sections['section_1_hero'], 'event' => $sections['section_4_event']])
        @include('invitation.sections.about', ['about' => $sections['section_2_about']])
        @include('invitation.sections.story', ['story' => $sections['section_3_story'], 'stories' => $stories])
        @include('invitation.sections.event', ['event' => $sections['section_4_event']])
        @include('invitation.sections.gallery', [
            'gallery' => $sections['section_5_gallery'],
            'bentoPhotos' => $bentoPhotos,
            'stripPhotos' => $stripPhotos,
        ])
        @include('invitation.sections.rsvp', [
            'rsvp' => $sections['section_6_rsvp'],
            'wishes' => $wishes,
            'guestName' => $guestName,
            'isPersonalizedGuest' => $isPersonalizedGuest,
        ])
    </main>

    @include('invitation.sections.lightbox', ['totalPhotos' => $bentoPhotos->count() + $stripPhotos->count()])
@endsection
