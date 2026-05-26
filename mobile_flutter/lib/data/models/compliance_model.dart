import 'package:json_annotation/json_annotation.dart';
import 'package:freezed_annotation/freezed_annotation.dart';

part 'compliance_model.freezed.dart';
part 'compliance_model.g.dart';

@freezed
class ComplianceModel with _$ComplianceModel {
  const factory ComplianceModel({
    String? id,
    @JsonKey(name: 'standard_code') String? standardCode,
    @JsonKey(name: 'control_id') String? controlId,
    @JsonKey(name: 'control_name') String? controlName,
    String? description,
    String? status,
    @JsonKey(name: 'implementation_status') String? implementationStatus,
    String? evidence,
    String? owner,
    @JsonKey(name: 'last_reviewed_at') DateTime? lastReviewedAt,
    @JsonKey(name: 'next_review_at') DateTime? nextReviewAt,
    @JsonKey(name: 'created_at') DateTime? createdAt,
  }) = _ComplianceModel;

  factory ComplianceModel.fromJson(Map<String, dynamic> json) => _$ComplianceModelFromJson(json);
}

extension ComplianceModelX on ComplianceModel {
  bool get isCompliant => status == 'compliant';
  bool get isPartial => status == 'partial';
  bool get isNonCompliant => status == 'non_compliant';
  
  String get statusDisplay {
    switch (status) {
      case 'compliant': return 'Tuân thủ';
      case 'partial': return 'Tuân thủ một phần';
      case 'non_compliant': return 'Không tuân thủ';
      default: return status ?? 'Unknown';
    }
  }
  
  Color get statusColor {
    switch (status) {
      case 'compliant': return Colors.green;
      case 'partial': return Colors.orange;
      case 'non_compliant': return Colors.red;
      default: return Colors.grey;
    }
  }
}