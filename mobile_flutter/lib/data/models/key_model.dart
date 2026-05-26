import 'package:json_annotation/json_annotation.dart';
import 'package:freezed_annotation/freezed_annotation.dart';

part 'key_model.freezed.dart';
part 'key_model.g.dart';

@freezed
class KeyModel with _$KeyModel {
  const factory KeyModel({
    String? id,
    @JsonKey(name: 'key_id') String? keyId,
    String? type,
    int? size,
    String? purpose,
    String? status,
    String? fingerprint,
    @JsonKey(name: 'public_key') String? publicKey,
    @JsonKey(name: 'expires_at') DateTime? expiresAt,
    @JsonKey(name: 'revoked_at') DateTime? revokedAt,
    @JsonKey(name: 'revocation_reason') String? revocationReason,
    dynamic metadata,
    List<String>? tags,
    @JsonKey(name: 'created_by') String? createdBy,
    @JsonKey(name: 'created_at') DateTime? createdAt,
  }) = _KeyModel;

  factory KeyModel.fromJson(Map<String, dynamic> json) => _$KeyModelFromJson(json);
}

extension KeyModelX on KeyModel {
  bool get isActive => status == 'active';
  bool get isRevoked => status == 'revoked';
  bool get isExpired => expiresAt != null && expiresAt!.isBefore(DateTime.now());
  
  String get typeDisplay {
    switch (type?.toUpperCase()) {
      case 'RSA': return 'RSA';
      case 'AES': return 'AES';
      case 'ECC': return 'ECC';
      default: return type ?? 'Unknown';
    }
  }
  
  String get statusDisplay {
    if (isExpired) return 'Hết hạn';
    switch (status) {
      case 'active': return 'Hoạt động';
      case 'revoked': return 'Thu hồi';
      default: return status ?? 'Unknown';
    }
  }
  
  Color get statusColor {
    if (isExpired) return Colors.orange;
    switch (status) {
      case 'active': return Colors.green;
      case 'revoked': return Colors.red;
      default: return Colors.grey;
    }
  }
}