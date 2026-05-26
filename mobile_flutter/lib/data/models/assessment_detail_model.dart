import 'package:json_annotation/json_annotation.dart';
import 'package:freezed_annotation/freezed_annotation.dart';

part 'assessment_detail_model.freezed.dart';
part 'assessment_detail_model.g.dart';

@freezed
class AssessmentDetailModel with _$AssessmentDetailModel {
  const factory AssessmentDetailModel({
    String? id,
    @JsonKey(name: 'assessment_id') String? assessmentId,
    @JsonKey(name: 'criteria_id') String? criteriaId,
    String? response,
    double? score,
    dynamic evidence,
    String? comments,
    String? status,
    @JsonKey(name: 'reviewed_by') String? reviewedBy,
    @JsonKey(name: 'reviewed_at') DateTime? reviewedAt,
    @JsonKey(name: 'created_at') DateTime? createdAt,
  }) = _AssessmentDetailModel;

  factory AssessmentDetailModel.fromJson(Map<String, dynamic> json) => _$AssessmentDetailModelFromJson(json);
}

extension AssessmentDetailModelX on AssessmentDetailModel {
  bool get isPending => status == 'pending';
  bool get isCompleted => status == 'completed';
  bool get isReviewed => status == 'reviewed';
  bool get hasEvidence => evidence != null;
}