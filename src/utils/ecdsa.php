<?php

require __DIR__ ."/../vendor/autoload.php";

use Mdanter\Ecc\Crypto\Signature\SignHasher;
use Mdanter\Ecc\EccFactory;
use Mdanter\Ecc\Random\RandomGeneratorFactory;
use Mdanter\Ecc\Crypto\Signature\Signer;
use Mdanter\Ecc\Serializer\PrivateKey\PemPrivateKeySerializer;
use Mdanter\Ecc\Serializer\PrivateKey\DerPrivateKeySerializer;
use Mdanter\Ecc\Serializer\PublicKey\PemPublicKeySerializer;
use Mdanter\Ecc\Serializer\PublicKey\DerPublicKeySerializer;
use Mdanter\Ecc\Serializer\Signature\DerSignatureSerializer;

function doSign($sk, $data){
    $adapter = EccFactory::getAdapter();
    $generator = EccFactory::getNistCurves()->generator384();

    $pem_serializer = new PemPrivateKeySerializer(new DerPrivateKeySerializer($adaptor));
    $key = $pem_serializer->parse($sk);

    $algorithm = 'sha256';
    $hasher = new SignHasher($algorithm);
    $hashed_data = $hasher->makeHash($data, $generator);

    $random = RandomGeneratorFactory::getHmacRandomGenerator($key, $hashed_data, $algorithm);
    $random_k = $random->generate($generator->getOrder());

    $signer = new Signer($adapter);
    $signature = $signer->sign($key, $hashed_data, $random_k);

    $serializer = new DerSignatureSerializer();
    $serialized_sig = $serializer->serialize($signature);
    return base64_encode($serialized_sig);
}

function doVerify($pk, $sig, $data){
    $sig_data = base64_decode($sig);

    $sig_serializer = new DerSignatureSerializer();
    $sig = $sigSerializer -> parse($sig_data);

    $adapter = EccFactory::getAdapter();
    $generator = EccFactory::getNistCurves()->generator384();

    $der_serializer = new DerPublicKeySerializer($adapter);
    $pem_serializer = new PemPublicKeySerializer($der_serializer);

    $hasher = new SignHasher('sha256');
    $hashed_data = $hasher->makeHash($data, $generator);

    $signer = new Signer($adapter);
    
    return $signer->verify($pk, $sig, $hashed_data);
}